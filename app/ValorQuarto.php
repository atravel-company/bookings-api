<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ValorQuarto extends Model
{
    use SoftDeletes;
    
    protected $fillable = ['pedido_produto_id','valor_quarto', 'valor_extra', 'kick', 'markup', 'total', 'profit'];

    protected $appends = ['ValorKick', 'ValorMarkup'];

    public function getValorMarkupAttribute(){
    	$mkp = ($this->markup != null ? $this->markup : 0);
    	$temp = $this->valor_quarto * ( $mkp / 100);
    	return number_format( floor($temp*100)/100, 2 , ",", ".");
    }

    public function getValorKickAttribute(){
    	$temp = $this->valor_quarto * ($this->kick/100);
    	return number_format( floor($temp*100)/100, 2 , ",", ".");
    }
    
}