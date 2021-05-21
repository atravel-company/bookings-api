<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PedidoQuarto extends Model
{
//    use SoftDeletes;

    protected $fillable = ['pedido_produto_id','checkin', 'checkout', 'type', 'rooms', 'plan', 'people', 'remark', 'night', 'offer_name', 'offer', 'price', 'total', 'ats_rate', 'ats_total_rate', 'profit','days'];

	protected $dates = ['created_at', 'updated_at'];

	protected $appends = ['rnts', 'bednight'];

	
    public function pedidoquartoroom(){

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


	public function getRntsAttribute(){
		return ($this->days * $this->rooms);
	}

	public function getBedNightAttribute(){
		return ($this->days * $this->people);
	}

}
