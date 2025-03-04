<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class PedidoTicket extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    protected $fillable = ['pedido_produto_id', 'data', 'hora', 'adult', 'children', 'babie', 'remark', 'total', 'ats_rate', 'profit', 'remark_internal'];

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
        $data = PedidoTicket::where('pedido_produto_id', $this->pedido_produto_id)->get()->sum('ats_rate');

        return $data;
    }
}
