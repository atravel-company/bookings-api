<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PedidoProduto extends Model
{
    //use SoftDeletes;
  
    protected $fillable = ['pedido_geral_id','produto_id', 'valor', 'profit', 'email_check'];
    protected $guarded = ['id', 'created_at', 'update_at'];
    protected $table = 'pedido_produto';

    protected $appends = ['tipoproduto', 'FirstCheckin'];

    protected $dates = ["FirstCheckin"];

    //dinamicrel vai ter o mesmo valor de tipoproduto. Mas sera usado para chamar um dos relacionamentos de pedidoproduto
    // ex : dinamicrel = quarto , PedidoProduto::first()->pedido.$dinamicre()->.....


    protected $pedidoPath = "Pedido";
    protected $valorPath = "Valor";


    public function quartos()
    {
        return $this->hasMany(PedidoQuarto::class, 'pedido_produto_id', 'id');
    }


    public function pedidogeral()
    {
        return $this->belongsTo(PedidoGeral::class, 'pedido_geral_id', 'id');
    }



    /* HENRIQUE */

    public function produto()
    {
        return $this->belongsTo(Produto::class);
    }
    public function pedidoquarto()
    {
        return $this->hasMany(PedidoQuarto::class)->orderBy('checkin', 'DESC');
    }
    public function pedidocar()
    {
        return $this->hasMany(PedidoCar::class)->orderBy('pickup_data', 'DESC');
    }
    public function pedidogame()
    {
        return $this->hasMany(PedidoGame::class)->orderBy('data', 'DESC');
    }
    public function pedidoticket()
    {
        return $this->hasMany(PedidoTicket::class)->orderBy('data', 'DESC');
    }
    public function pedidotransfer()
    {
        return $this->hasMany(PedidoTransfer::class)->orderBy('data', 'DESC');
    }

    public function valorquarto()
    {
        return $this->belongsTo(ValorQuarto::class, 'id', 'pedido_produto_id');
    }
    public function valorcar()
    {
        return $this->belongsTo(ValorCar::class, 'id', 'pedido_produto_id');
    }
    public function valorgame()
    {
        return $this->belongsTo(ValorGolf::class, 'id', 'pedido_produto_id');
    }
    public function valorticket()
    {
        return $this->belongsTo(ValorTicket::class, 'id', 'pedido_produto_id');
    }
    public function valortransfer()
    {
        return $this->belongsTo(ValorTransfer::class, 'id', 'pedido_produto_id');
    }

    public function extras()
    {
        return $this->hasMany(PedidoProdutoExtra::class);
    }


    public function getTipoProdutoAttribute()
    {
        if ($this->valorquarto != null) {
            return "quarto";
        }
        if ($this->valorcar != null) {
            return "car";
        }
        if ($this->valorgame != null) {
            return "game";
        }
        if ($this->valorticket != null) {
            return "ticket";
        }
        if ($this->valortransfer != null) {
            return "transfer";
        }

        return null;
    }


    public function getFirstCheckinAttribute()
    {
        try {
            if ($this->TipoProduto == null) {
                return null;
            }

            $rel = "pedido".$this->tipoProduto;

            $field = "data";
            switch ($this->tipoProduto) {
          case "quarto":
            $field = "checkin";
            break;
          case "car":
            $field = "pickup_data";
            break;
        }
        
            $data =  $this->$rel()->get()->sortBy(function ($col) use ($field) {
                return $col->checkin;
            })->values()->first();

            if ($data) {
                return $data->$field;
            }
        } catch (ModelNotFoundException $ex) {
            dd($ex);
        }
    }

    public function getTotalOfRelatioAttribute($rel)
    {
    }
}
