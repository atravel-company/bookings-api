<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PedidoPayments extends Model
{
  protected $fillable = ['pedido_geral_id','date', 'payment'];
  protected $guarded = ['id', 'created_at', 'update_at'];
  protected $table = 'pedido_payments';

  	public function pedido(){

		return $this->belongsTo('App\PedidoGeral');
	}
}
