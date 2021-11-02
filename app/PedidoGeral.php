<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class PedidoGeral extends Model implements Auditable
{
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = ['id', 'type', 'lead_name', 'responsavel', 'referencia', 'user_id', 'valor', 'profit', 'status', 'deleted_at'];
    protected $appends = ['valortotalquarto', 'valortotalgolf', 'valortotaltransfer', 'valortotalcar', 'valortotalextras', 'valortotalkickback', 'TotalPagamento', 'ValorTotalProfitExtras', 'AtsTotalExtra', 'DataFirstServico', 'DataFirstServicoDesc'];
    protected $dates = ['DataFirstServico', 'DataFirstServicoDesc'];

    public function produtoss()
    {
        return $this->belongsToMany('App\Produto', 'pedido_produto')->withTrashed()->withTimestamps()->withPivot('id', 'valor', 'email_check');
    }

    public function getTotalPayment()
    {

        return $this->hasMany(PedidoPayments::class, 'pedido_geral_id', 'id')->orderBy('payment');
    }

    public function produtos()
    {

        return $pedidos_produtos = $this->hasMany(PedidoProduto::class, 'pedido_geral_id', 'id');
    }

    /* HENRIQUE */

    public function pedidoprodutos()
    {
        return $this->hasMany(PedidoProduto::class, 'pedido_geral_id', 'id');
    }

    public function payments()
    {
        return $this->hasMany(PedidoPayments::class);
    }

    public function reports()
    {
        return $this->belongsTo(PedidoReport::class, 'id', 'pedido_geral_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    public function scopePendings($query)
    {
        return $query->where('status', 'In Progress')
            ->orWhere('status', 'Waiting Confirmation');
    }

    public function scopeViewWithAllProd($query, $userRequest = false)
    {

        if (isset($userRequest['start'])) {
            $userRequest['start'] = Carbon::parse(str_replace('/', '-', $userRequest['start']))->format("Y-m-d");
        } else {
            $userRequest['start'] = null;
        }

        if (isset($userRequest['end'])) {
            $userRequest['end'] = Carbon::parse(str_replace('/', '-', $userRequest['end']))->format("Y-m-d");
        } else {
            $userRequest['end'] = Carbon::now()->format("Y-m-d");
        }

        $data = $query->where("status", "!=", "Canceled")->with('payments')->with('user')
            ->with(['pedidoprodutos' => function ($sql) use ($userRequest) {
                $sql->with('extras');
                $sql->with('valorquarto');
                $sql->with('pedidoquarto');
                $sql->with('valortransfer');
                $sql->with('pedidotransfer');
                $sql->with('valorgame');
                $sql->with('pedidogame');
                $sql->with('valorcar');
                $sql->with('pedidocar');
                $sql->with('valorticket');
                $sql->with('pedidoticket');
                $sql->with('produto');
            }]);

        if (isset($userRequest['start'])) {
            if ($userRequest['start'] !== null) {
                $data = $data->customDataFilters($userRequest);
            }
        }

        return $data;
    }

    public function scopeCustomDataFilters($query, $data)
    {

        if ($data['start'] !== null) {
            $query = $query->whereHas('pedidoprodutos.pedidoquarto', function ($q) use ($data) {
                $q->whereBetween('checkin', [$data['start'], $data['end']]);
            })->orWhereHas('pedidoprodutos.pedidotransfer', function ($q) use ($data) {
                $q->whereBetween('data', [$data['start'], $data['end']]);
            })->orWhereHas('pedidoprodutos.pedidogame', function ($q) use ($data) {
                $q->whereBetween('data', [$data['start'], $data['end']]);
            })->orWhereHas('pedidoprodutos.pedidocar', function ($q) use ($data) {
                $q->whereBetween('pickup_data', [$data['start'], $data['end']]);
            })->orWhereHas('pedidoprodutos.pedidoticket', function ($q) use ($data) {
                $q->whereBetween('data', [$data['start'], $data['end']]);
            });
        }

        return $query;
    }

    public function formatDecimal($value)
    {
        try {
            return number_format(floor($value * 100) / 100, 2, ".", ",");
        } catch (\Throwable $th) {
           dd( $th );
        }
    }

    /* MUTATORS ***** */

    public function getTotalPagamentoAttribute()
    {
        $val = 0;
        foreach ($this->payments as $p) {
            $val += $p->payment;
        }

        return number_format(floor($val * 100) / 100, 2, ".", ",");
    }

    public function getValorNaoPagoAttribute()
    {
        $pay = 0;
        foreach ($this->payments as $p) {
            $pay += $p->payment;
        }

        // $val = ( $this->valor - $pay ); /* Alterado dia 02-03-2020 */
        $val = ($pay - $this->valor);
        return number_format($val, 2, ".", ",");
    }

    public function getValorTotalQuartoAttribute()
    {

        $total = 0;
        foreach ($this->pedidoprodutos as $pedidoprodutos) {
            if ($pedidoprodutos->valorquarto) {
                $total += $pedidoprodutos->valorquarto->valor_quarto;
            }
        }

        return $this->formatDecimal($total);
    }

    public function getValorTotalGolfAttribute()
    {

        $total = 0;

        foreach ($this->pedidoprodutos as $pedidoprodutos) {
            if ($pedidoprodutos->valorgame) {$total += $pedidoprodutos->valorgame->valor_golf;}
        }

        return $this->formatDecimal($total);
    }

    public function getValorTotalCarAttribute()
    {

        $total = 0;
        foreach ($this->pedidoprodutos as $pedidoprodutos) {
            if ($pedidoprodutos->valorcar) {$total += $pedidoprodutos->valorcar->valor_car;}
        }
        return $this->formatDecimal($total);
    }

    public function getValorTotalTransferAttribute()
    {

        $total = 0;
        foreach ($this->pedidoprodutos as $pedidoprodutos) {
            if ($pedidoprodutos->valortransfer) {$total += $pedidoprodutos->valortransfer->valor_transfer;}
        }
        return $this->formatDecimal($total);
    }

    public function setExtasConfig()
    {

        $this->extras_total = 0;
        $this->profits_extras = 0;

        foreach ($this->pedidoprodutos as $pedidoprodutos) {
            if ($pedidoprodutos->extras) {
                foreach ($pedidoprodutos->extras as $extra) {
                    $this->extras_total += $extra->total;
                    $this->profits_extras += $extra->profit;
                }
            }
        }
    }

    public function getValorTotalExtrasAttribute()
    {
        $this->setExtasConfig();
        return $this->formatDecimal($this->extras_total);
    }

    public function getValorTotalProfitExtrasAttribute()
    {
        $this->setExtasConfig();
        return $this->formatDecimal($this->profits_extras);
    }

    public function getValorTotalKickBackAttribute()
    {

        $total = 0;
        foreach ($this->pedidoprodutos as $pedidoprodutos) {

            $tipo = $pedidoprodutos->tipoproduto;

            if ($tipo == null) {
                return 0;
            }

            $rel = "valor" . $pedidoprodutos->tipoproduto;
            $field = "valor_" . $pedidoprodutos->tipoproduto;
            if ($pedidoprodutos->$rel) {
                if ($pedidoprodutos->$rel->kick != null) {
                    $total += ($pedidoprodutos->$rel->$field * ($pedidoprodutos->$rel->kick / 100));
                }
            }
        }

        return $this->formatDecimal($total);
    }

    public function getDataFirstServicoAttribute()
    {

        try {

            $data = $this->pedidoprodutos()->get();

            if ($data) {
                $response = $data->sortBy(function ($col) {
                    return $col->firstCheckin;

                })->values()->first();

                if ($response) {
                    return $response->firstCheckin;
                } else {
                    return Carbon::parse("00/01/0009");
                }
            } else {
                return Carbon::parse("00/01/0009");
            }

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $ex) {
            dd($ex);
        }
    }

    public function getDataFirstServicoDescAttribute()
    {

        $data = $this->pedidoprodutos()->get();

        if ($data) {
            $response = $data->sortByDesc(function ($col) {
                return $col->firstCheckin;

            })->values()->first();

            if ($response) {
                return $response->firstCheckin;
            } else {
                return Carbon::parse("00/01/0009");
            }
        } else {
            return Carbon::parse("00/01/0009");
        }
    }

    public function getAtsTotalQuartoAttribute()
    {
        $total = 0;
        foreach ($this->pedidoprodutos as $pedidoprodutos) {
            if ($pedidoprodutos->valorquarto) {$total = $pedidoprodutos->valorquarto->valor_quarto - $pedidoprodutos->valorquarto->profit;}
        }
        return $this->formatDecimal($total);
    }

    public function getAtsTotalGolfAttribute()
    {
        $total = 0;
        foreach ($this->pedidoprodutos as $pedidoprodutos) {
            if ($pedidoprodutos->valorgame) {$total = $pedidoprodutos->valorgame->valor_golf - $pedidoprodutos->valorgame->profit;}
        }
        return $this->formatDecimal($total);
    }

    public function getAtsTotalTransferAttribute()
    {
        $total = 0;
        foreach ($this->pedidoprodutos as $pedidoprodutos) {
            if ($pedidoprodutos->valortransfer) {$total = $pedidoprodutos->valortransfer->valor_transfer - $pedidoprodutos->valortransfer->profit;}
        }
        return $this->formatDecimal($total);
    }

    public function getAtsTotalCarAttribute()
    {
        $total = 0;
        foreach ($this->pedidoprodutos as $pedidoprodutos) {
            if ($pedidoprodutos->valorcar) {
                $total = $pedidoprodutos->valorcar->valor_car - $pedidoprodutos->valorcar->profit;
            }
        }
        return $this->formatDecimal($total);
    }

    public function getAtsTotalExtraAttribute()
    {

        try{

            $temp = str_replace(".", "@", $this->valortotalextras);
            $temp = str_replace(",", "", $temp);
            $temp = str_replace("@", ".", $temp);
            $val  = $temp;
            $temp = str_replace(".", "@", $this->ValorTotalProfitExtras);
            $temp = str_replace(",", "", $temp);
            $temp = str_replace("@", ".", $temp);
            $val2 = $temp;

            return $this->formatDecimal($val - $val2);

        }catch(Exception $ex){

            dd("pedidogeral execption", $ex);
        }

    }

}
