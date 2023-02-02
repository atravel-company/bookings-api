<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class PedidoCar extends Model implements Auditable
{
    //use SoftDeletes;
    use \OwenIt\Auditing\Auditable;


    protected $fillable = ['pedido_produto_id', 'pickup', 'pickup_data', 'pickup_hora', 'pickup_flight', 'pickup_country', 'pickup_airport', 'dropoff', 'dropoff_data', 'dropoff_hora', 'dropoff_flight', 'dropoff_country', 'dropoff_airport', 'remark', 'group', 'model', 'rate', 'days', 'tax', 'tax_type', 'total', 'ats_rate', 'ats_total_rate', 'profit', 'remark_internal'];

    protected $appends = ['checkin', 'TotalPax', 'ats_total_rate'];

    public function getCheckinAttribute()
    {
        return $this->pickup_data;
    }

    public function getTotalPaxAttribute()
    {
        return 0;
    }

    public function getAtsTotalRateAttribute()
    {
        return PedidoCar::where('pedido_produto_id', $this->pedido_produto_id)->get()->sum('ats_rate');
    }
}
