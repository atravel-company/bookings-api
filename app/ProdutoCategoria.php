<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProdutoCategoria extends Model
{
		use SoftDeletes;

    protected $fillable = ['produto_id','categoria_id'];
	  protected $guarded = ['id', 'created_at', 'update_at'];
	  protected $table = 'produto_categoria';
}
