<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class PedidoQuartoRoomName extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    protected $fillable = ['pedido_quarto_room_id', 'name'];
    protected $dates = ['created_at', 'updated_at'];


    public function pedidoquartoroom()
    {
        return $this->belongsTo('App\PedidoQuartoRoom');
    }
}
