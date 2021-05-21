<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Categoria extends Model
{
	use SoftDeletes;

    //
    protected $fillable = ['name','description', 'path_image'];

    public function produtos(){
		
		return $this->belongsToMany('App\Produto','produto_categoria')->where('estado', 1)->withTimestamps()->orderBy('nome');
	}

}
