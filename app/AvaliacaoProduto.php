<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AvaliacaoProduto extends Model
{
    //
    protected $fillable = [
		'produto_id',
		'user_id',
		'nota',
		'comentario'
	];

	public function produto(){
		
		return $this->belongsTo('App\Produto');
	}

	public function user(){
		
		return $this->belongsTo('App\User');
	}
}
