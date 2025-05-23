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

    public function scopeViewWithAllProd($query, $userRequest = [], $produtoIdOrIds = null) // Default to empty array
    {
        $eagerLoadPedidoProdutos = function ($sql) use ($produtoIdOrIds) {
            // If produtoIdOrIds is provided, constrain the loaded pedidoprodutos
            if ($produtoIdOrIds !== null) {
                if (is_array($produtoIdOrIds) && !empty($produtoIdOrIds)) {
                    $sql->whereIn('pedido_produto.produto_id', $produtoIdOrIds); // Use qualified column name for clarity
                } elseif (!is_array($produtoIdOrIds) && $produtoIdOrIds) { // Ensure it's not null/empty if single
                    $sql->where('pedido_produto.produto_id', $produtoIdOrIds);
                }
            }
            // Load common nested relationships for the (potentially filtered) pedidoprodutos
            $sql->with('extras', 'valorquarto', 'pedidoquarto', 'valortransfer', 'pedidotransfer', 'valorgame', 'pedidogame', 'valorcar', 'pedidocar', 'valorticket', 'pedidoticket', 'produto');
        };

        // Base Eager Loading - Load relationships needed in almost all cases
        // Consider if *all* of these are *always* needed. Reducing eager loading helps.
        $query->with('payments', 'user')
              ->with(['pedidoprodutos' => $eagerLoadPedidoProdutos]);

        // Prepare dates - Use Carbon directly for parsing
        $startDate = null;
        if (isset($userRequest['start']) && !empty($userRequest['start'])) {
            try {
                // Assuming format d/m/Y from your original code
                $startDate = Carbon::createFromFormat("d/m/Y", $userRequest['start'])->format('Y-m-d');
            } catch (\Exception $e) { $startDate = null; /* Handle invalid date format */ }
        }

        $endDate = null;
        if (isset($userRequest['end']) && !empty($userRequest['end'])) {
            try {
                $endDate = Carbon::createFromFormat("d/m/Y", $userRequest['end'])->format('Y-m-d');
            } catch (\Exception $e) { $endDate = null; /* Handle invalid date format */ }
        } else if ($startDate) {
            // Default end date if start is provided but end isn't (based on original code)
            $endDate = Carbon::now()->format("Y-m-d");
        }


        // Apply date filters using database queries if dates are valid
        if ($startDate || $endDate) {
            // Pass parsed dates to the custom filter scope
            $query->customDataFilters(['start' => $startDate, 'end' => $endDate]);
        }

        // Removed: $query->where("status", "!=", "Canceled"); // Moved to controller for clarity

        return $query;
    }

    public function scopeCustomDataFilters($query, $data)
    {
        $start = $data['start'] ?? null;
        $end = $data['end'] ?? null;

        // Apply date range filtering using WHERE HAS clauses within a grouped WHERE
        // This ensures that at least one related product type falls within the date range.
        if ($start && $end) {
            $query->where(function ($subQuery) use ($start, $end) {
                $subQuery->orWhereHas('pedidoprodutos.pedidoquarto', function ($q) use ($start, $end) { $q->whereBetween('checkin', [$start, $end]); })
                        ->orWhereHas('pedidoprodutos.pedidotransfer', function ($q) use ($start, $end) { $q->whereBetween('data', [$start, $end]); })
                        ->orWhereHas('pedidoprodutos.pedidogame', function ($q) use ($start, $end) { $q->whereBetween('data', [$start, $end]); })
                        ->orWhereHas('pedidoprodutos.pedidocar', function ($q) use ($start, $end) { $q->whereBetween('pickup_data', [$start, $end]); })
                        ->orWhereHas('pedidoprodutos.pedidoticket', function ($q) use ($start, $end) { $q->whereBetween('data', [$start, $end]); });
            });
        } elseif ($start) { // Only start date provided
            $query->where(function ($subQuery) use ($start) {
                $subQuery->orWhereHas('pedidoprodutos.pedidoquarto', function ($q) use ($start) { $q->where('checkin', '>=', $start); })
                        ->orWhereHas('pedidoprodutos.pedidotransfer', function ($q) use ($start) { $q->where('data', '>=', $start); })
                        ->orWhereHas('pedidoprodutos.pedidogame', function ($q) use ($start) { $q->where('data', '>=', $start); })
                        ->orWhereHas('pedidoprodutos.pedidocar', function ($q) use ($start) { $q->where('pickup_data', '>=', $start); })
                        ->orWhereHas('pedidoprodutos.pedidoticket', function ($q) use ($start) { $q->where('data', '>=', $start); });
            });
        } elseif ($end) { // Only end date provided
            $query->where(function ($subQuery) use ($end) {
                $subQuery->orWhereHas('pedidoprodutos.pedidoquarto', function ($q) use ($end) { $q->where('checkin', '<=', $end); })
                        ->orWhereHas('pedidoprodutos.pedidotransfer', function ($q) use ($end) { $q->where('data', '<=', $end); })
                        ->orWhereHas('pedidoprodutos.pedidogame', function ($q) use ($end) { $q->where('data', '<=', $end); })
                        ->orWhereHas('pedidoprodutos.pedidocar', function ($q) use ($end) { $q->where('pickup_data', '<=', $end); })
                        ->orWhereHas('pedidoprodutos.pedidoticket', function ($q) use ($end) { $q->where('data', '<=', $end); });
            });
        }
        // If neither $start nor $end, this scope won't add date constraints.

        return $query; // Make sure to return the query builder instance
    }

    public function formatDecimal($value)
    {
        try {
            return number_format(floor($value * 100) / 100, 2, ".", ",");
        } catch (\Throwable $th) {
            dd($th);
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
            if ($pedidoprodutos->valorgame) {
                $total += $pedidoprodutos->valorgame->valor_golf;
            }
        }

        return $this->formatDecimal($total);
    }

    public function getValorTotalCarAttribute()
    {

        $total = 0;
        foreach ($this->pedidoprodutos as $pedidoprodutos) {
            if ($pedidoprodutos->valorcar) {
                $total += $pedidoprodutos->valorcar->valor_car;
            }
        }
        return $this->formatDecimal($total);
    }

    public function getValorTotalTransferAttribute()
    {

        $total = 0;
        foreach ($this->pedidoprodutos as $pedidoprodutos) {
            if ($pedidoprodutos->valortransfer) {
                $total += $pedidoprodutos->valortransfer->valor_transfer;
            }
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
            $field = 'valor_';
            $field .= $pedidoprodutos->tipoproduto == 'game' ? 'golf' : $pedidoprodutos->tipoproduto;

            if ($pedidoprodutos->$rel) {
                if ($pedidoprodutos->$rel->kick != null) {
                    $total += ($pedidoprodutos->$rel->$field * ($pedidoprodutos->$rel->kick / 100));
                }
            }
        }

        return $this->formatDecimal($total);
    }

    public function getAtsTotalQuartoAttribute()
    {
        $total = 0;
        foreach ($this->pedidoprodutos as $pedidoprodutos) {
            if ($pedidoprodutos->valorquarto) {
                $total += $pedidoprodutos->valorquarto->valor_quarto - $pedidoprodutos->valorquarto->profit;
            }
        }
        return $this->formatDecimal($total);
    }

    public function getAtsTotalGolfAttribute()
    {
        $total = 0;
        foreach ($this->pedidoprodutos as $pedidoprodutos) {
            if ($pedidoprodutos->valorgame) {
                $total += $pedidoprodutos->valorgame->valor_golf - $pedidoprodutos->valorgame->profit;
            }
        }
        return $this->formatDecimal($total);
    }

    public function getAtsTotalTransferAttribute()
    {
        $total = 0;
        foreach ($this->pedidoprodutos as $pedidoprodutos) {
            if ($pedidoprodutos->valortransfer) {
                $total += $pedidoprodutos->valortransfer->valor_transfer - $pedidoprodutos->valortransfer->profit;
            }
        }
        return $this->formatDecimal($total);
    }

    public function getAtsTotalCarAttribute()
    {
        $total = 0;
        foreach ($this->pedidoprodutos as $pedidoprodutos) {
            if ($pedidoprodutos->valorcar) {
                $total += $pedidoprodutos->valorcar->valor_car - $pedidoprodutos->valorcar->profit;
            }
        }
        return $this->formatDecimal($total);
    }

    public function getAtsTotalExtraAttribute()
    {
        try {
            $temp = str_replace(".", "@", $this->valortotalextras);
            $temp = str_replace(",", "", $temp);
            $temp = str_replace("@", ".", $temp);
            $val  = $temp;
            $temp = str_replace(".", "@", $this->ValorTotalProfitExtras);
            $temp = str_replace(",", "", $temp);
            $temp = str_replace("@", ".", $temp);
            $val2 = $temp;

            return $this->formatDecimal($val - $val2);
        } catch (Exception $ex) {

            dd("pedidogeral execption", $ex);
        }
    }

    /**
     * Get the overall earliest service date for this PedidoGeral.
     * Relies on 'pedidoprodutos' and their nested date relations being EAGER-LOADED.
     *
     * @return \Carbon\Carbon|null
     */
    public function getDataFirstServicoAttribute(): ?Carbon
    {
        // Ensure the main relationship is loaded
        if (! $this->relationLoaded('pedidoprodutos')) {
            // It's generally bad practice to lazy-load in an accessor,
            // return null or default if data wasn't explicitly loaded.
            // If this happens, fix the eager loading in the controller (Part 1).
            return Carbon::parse("00/01/0009"); // Or return null;
        }

        // Map over the loaded collection, get each item's firstCheckin, filter out nulls
        $dates = $this->pedidoprodutos
            ->map(function ($pedidoProduto) {
                // Uses the optimized getFirstCheckinAttribute from PedidoProduto
                return $pedidoProduto->FirstCheckin;
            })
            ->filter(); // Remove any null dates

        // Find the minimum date from the collection of valid dates
        if ($dates->isEmpty()) {
            // Return the original default date if no valid dates found
            return Carbon::parse("00/01/0009"); // Return as Carbon object
        } else {
            return $dates->min(); // Returns the earliest Carbon date object
        }
    }

    /**
     * Get the overall latest service date for this PedidoGeral.
     * Relies on 'pedidoprodutos' and their nested date relations being EAGER-LOADED.
     *
     * @return \Carbon\Carbon|null
     */
    public function getDataFirstServicoDescAttribute(): ?Carbon
    {
        if (! $this->relationLoaded('pedidoprodutos')) {
             return Carbon::parse("00/01/0009"); // Or return null;
        }

        $dates = $this->pedidoprodutos
            ->map(function ($pp) { return $pp->firstCheckin; })
            ->filter();

        if ($dates->isEmpty()) {
            return Carbon::parse("00/01/0009");
        } else {
            // Use max() to find the latest date
            return $dates->max();
        }
    }
}
