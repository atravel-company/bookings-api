<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;


class PedidoQuarto extends Model implements Auditable
{
    //    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = ['pedido_produto_id', 'checkin', 'checkout', 'type', 'rooms', 'plan', 'people', 'remark', 'night', 'offer_name', 'offer', 'price', 'total', 'ats_rate', 'ats_total_rate', 'profit', 'days', 'remark_internal'];

    protected $dates = ['created_at', 'updated_at'];

    protected $casts = [
        'checkin' => 'date',
        'checkout' => 'date',
    ];

    protected $appends = ['rnts', 'bednight', 'ats_total_rate'];


    public function pedidoquartoroom()
    {

        return $this->hasMany('App\PedidoQuartoRoom');
    }

    public function pedido()
    {
        return $this->belongsTo('App\PedidoGeral');
    }

    public function produto()
    {
        return $this->belongsTo(PedidoProduto::class, 'pedido_produto_id', 'id');
    }


    public function getRntsAttribute()
    {
        return ($this->days * $this->rooms);
    }

    public function getBedNightAttribute()
    {
        return ($this->days * $this->people);
    }

    public function getAtsTotalRateAttribute()
    {
        return PedidoQuarto::where('pedido_produto_id', $this->pedido_produto_id)->get()->sum('ats_rate');
    }
}
