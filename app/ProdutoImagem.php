<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProdutoImagem extends Model
{
	use SoftDeletes;

  protected $fillable = ['produto_id','title','description','path_image'];

 //    public function produto(){
		
	// 	return $this->belongsTo('App\Produto');
	// }
}
