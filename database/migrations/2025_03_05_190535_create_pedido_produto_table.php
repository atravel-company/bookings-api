<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePedidoProdutoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pedido_produto', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('pedido_geral_id')->nullable();
            $table->unsignedInteger('produto_id')->nullable();
            $table->timestamps();
            $table->double('valor', 15, 2)->nullable();
            $table->double('profit', 15, 2)->nullable();
            $table->string('email_check')->nullable();

            $table->index(['id', 'pedido_geral_id', 'produto_id'], 'pedido_produto_id_IDX');
            $table->index('pedido_geral_id');
            $table->index('produto_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pedido_produto');
    }
}