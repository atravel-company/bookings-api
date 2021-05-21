<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ValorGolf extends Model
{
    use SoftDeletes;
    
    protected $fillable = ['pedido_produto_id','valor_golf', 'valor_extra', 'kick', 'markup', 'total', 'profit'];

    protected $appends = ['ValorKick', 'ValorMarkup'];

    public function getValorMarkupAttribute(){
    	$temp = $this->valor_golf * ( ($this->markup != null ? $this->markup : 0) / 100);
    	return number_format( floor($temp*100)/100, 2 , ",", ".");
    }

    public function getValorKickAttribute(){
    	$temp = $this->valor_golf * ($this->kick/100);
    	return number_format( floor($temp*100)/100, 2 , ",", ".");
    }
}
