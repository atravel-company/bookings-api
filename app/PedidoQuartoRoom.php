<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PedidoQuartoRoom extends Model
{
  use SoftDeletes;

	protected $fillable = ['pedido_quarto_id','remark'];
	protected $dates = ['created_at', 'updated_at'];

    public function pedidoquartoroomname(){

		return $this->hasMany('App\PedidoQuartoRoomName');
	}

	public function roomname(){

		return $this->hasManyThrough('App\PedidoQuartoRoomName');
	}

	public function pedidoquarto()
	{
		return $this->belongsTo(PedidoQuarto::class, 'pedido_quarto_id', 'id');
	}
}
