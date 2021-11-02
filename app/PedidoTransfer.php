<?php

namespace App;

use App\PedidoProduto;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class PedidoTransfer extends Model implements Auditable
{
    //use SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = ['pedido_produto_id', 'data', 'hora', 'adult', 'children', 'babie', 'flight', 'pickup', 'dropoff', 'remark', 'company', 'total', 'ats_rate', 'profit'];

    protected $appends = ['checkin', 'TotalPax', 'ats_total_rate'];

    public function getCheckinAttribute()
    {
        return $this->data;
    }


    public function getTotalPaxAttribute()
    {
        return ($this->adult + $this->children + $this->babie);
    }

    public function getAtsTotalRateAttribute()
    {
        $data = PedidoTransfer::where('pedido_produto_id', $this->pedido_produto_id)->get()->sum('ats_rate');

        return $data;
    }
}
