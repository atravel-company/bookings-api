<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PedidoTicket extends Model
{
    //use SoftDeletes;
    
    protected $fillable = ['pedido_produto_id','data', 'hora', 'adult', 'children', 'babie', 'remark', 'total', 'ats_rate', 'profit'];

    protected $appends = ['checkin', 'TotalPax'];
    public function getCheckinAttribute(){
    	return $this->data;
    }

    public function getTotalPaxAttribute(){
    	return ($this->adult + $this->children + $this->babie);
    }
}
