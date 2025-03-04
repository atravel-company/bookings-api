<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Produto extends Model
{
		use SoftDeletes;

    //
    protected $fillable = ['nome','descricao', 'estado','categoria_id','destino_id','supplier_id', 'alojamento','golf', 'transfer', 'car', 'ticket', 'email','email_type', 'deleted_at'];

    public function avaliacoes(){

		return $this->hasMany('App\AvaliacaoProduto');
	}

	public function imagem(){

		return $this->hasMany('App\ProdutoImagem');
	}

	public function pdf(){

		return $this->hasMany('App\ProdutoPdf');
	}

	public function extras(){

		return $this->belongsToMany('App\Extra','produto_extra')->withTimestamps()->withPivot('formulario','extra_id');
	}

	public function categorias(){

		return $this->belongsToMany('App\Categoria','produto_categoria')->withTimestamps();
	}

	public function suppliers(){

		return $this->belongsTo('App\Supplier', 'supplier_id');
	}


	public function destinos(){

		return $this->belongsTo('App\Destinos', 'destino_id');
	}

}
