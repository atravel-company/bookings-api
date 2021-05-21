<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PedidoProdutoExtra extends Model
{
	use SoftDeletes;
	
  protected $fillable = ['pedido_produto_id','extra_id', 'tipo', 'amount', 'rate', 'total', 'ats_rate', 'ats_total_rate', 'profit'];
  protected $guarded = ['id', 'created_at', 'update_at', 'deleted_at'];
  protected $table = 'pedido_produto_extra';
}
