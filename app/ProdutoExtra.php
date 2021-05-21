<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProdutoExtra extends Model
{
    use SoftDeletes;
		
    protected $fillable = ['produto_id','extra_id', 'formulario'];
	  protected $guarded = ['id', 'created_at', 'update_at'];
	  protected $table = 'produto_extra';
}
