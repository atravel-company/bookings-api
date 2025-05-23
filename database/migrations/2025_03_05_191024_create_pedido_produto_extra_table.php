<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePedidoProdutoExtraTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pedido_produto_extra', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('pedido_produto_id')->nullable();
            $table->unsignedInteger('extra_id')->nullable();
            $table->string('tipo')->nullable();
            $table->timestamps();
            $table->integer('amount')->nullable();
            $table->double('rate', 15, 2)->nullable();
            $table->double('total', 15, 2)->nullable();
            $table->double('ats_rate', 15, 2)->nullable();
            $table->double('ats_total_rate', 15, 2)->nullable();
            $table->double('profit', 15, 2)->nullable();
            $table->softDeletes();

            $table->index('extra_id');
            $table->unique(['pedido_produto_id', 'extra_id'], 'pedido_produto_extra_pedido_produto_id_extra_id_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pedido_produto_extra');
    }
}