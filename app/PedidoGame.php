<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PedidoGame extends Model
{
    //use SoftDeletes;

    protected $fillable = ['pedido_produto_id','data', 'hora', 'course', 'people', 'remark', 'free', 'rate', 'total', 'ats_rate', 'ats_total_rate', 'profit', 'remark_internal'];

    protected $appends = ['checkin', 'TotalPax', 'ats_total_rate'];
    public function getCheckinAttribute(){
    	return $this->data;
    }

    public function getTotalPaxAttribute(){
    	return $this->people;
    }

    public function getAtsTotalRateAttribute()
    {
        return PedidoGame::where('pedido_produto_id', $this->pedido_produto_id)->get()->sum('ats_rate');
    }

}
