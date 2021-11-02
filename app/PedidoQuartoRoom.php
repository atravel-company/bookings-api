<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;


class PedidoQuartoRoom extends Model implements Auditable
{
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = ['pedido_quarto_id', 'remark'];
    protected $dates = ['created_at', 'updated_at'];

    public function pedidoquartoroomname()
    {

        return $this->hasMany('App\PedidoQuartoRoomName');
    }

    public function roomname()
    {

        return $this->hasManyThrough('App\PedidoQuartoRoomName');
    }

    public function pedidoquarto()
    {
        return $this->belongsTo(PedidoQuarto::class, 'pedido_quarto_id', 'id');
    }
}
