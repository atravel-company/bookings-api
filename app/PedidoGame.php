<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PedidoGame extends Model
{
    //use SoftDeletes;
    
    protected $fillable = ['pedido_produto_id','data', 'hora', 'course', 'people', 'remark', 'free', 'rate', 'total', 'ats_rate', 'ats_total_rate', 'profit'];

    protected $appends = ['checkin', 'TotalPax'];
    public function getCheckinAttribute(){
    	return $this->data;
    }

    public function getTotalPaxAttribute(){
    	return $this->people;
    }

}
