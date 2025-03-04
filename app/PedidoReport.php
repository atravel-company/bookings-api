<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PedidoReport extends Model
{
    protected $fillable = ['pedido_geral_id','pedido_produto_id', 'client', 'operador', 'produto', 'room_nights', 'checkout', 'checkin', 'valor_car', 'car_extra', 'car_kick', 'car_markup', 'valor_quarto', 'quarto_extra', 'quarto_kick', 'quarto_markup','valor_golf','golf_extra','golf_kick','golf_markup','valor_transfer','transfer_extra','transfer_kick','transfer_markup','valor_ticket','valor_ticket','tickets_extra','tickets_kick','tickets_markup','profit_quarto','profit_car','profit_golf','profit_transfer','profit_ticket'];

    protected $table = 'vw_pedidos_reports';

    public function payments(){

		return $this->hasMany('App\PedidoPayments', 'pedido_geral_id');
	}
}