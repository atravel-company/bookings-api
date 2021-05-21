<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PedidoQuartoRoomName extends Model
{
    //

	protected $fillable = ['pedido_quarto_room_id','name'];
	protected $dates = ['created_at', 'updated_at'];
	
    
    public function pedidoquartoroom()
	{
		return $this->belongsTo('App\PedidoQuartoRoom');
	}
}
