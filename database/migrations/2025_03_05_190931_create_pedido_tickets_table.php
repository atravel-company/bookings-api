<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePedidoTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pedido_tickets', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('pedido_produto_id')->nullable();
            $table->date('data')->nullable();
            $table->time('hora')->nullable();
            $table->integer('adult')->nullable();
            $table->integer('children')->nullable();
            $table->integer('babie')->nullable();
            $table->text('remark')->nullable();
            $table->timestamps();
            $table->double('total', 15, 2)->nullable();
            $table->double('ats_rate', 15, 2)->nullable();
            $table->double('profit', 15, 2)->nullable();
            $table->text('remark_internal')->nullable();

            $table->index(['id', 'pedido_produto_id'], 'pedido_tickets_id_IDX');
            $table->index(['data', 'hora'], 'pedido_tickets_data_IDX');
            $table->index('pedido_produto_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pedido_tickets');
    }
}