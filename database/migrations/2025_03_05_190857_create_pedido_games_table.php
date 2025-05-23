<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePedidoGamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pedido_games', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('pedido_produto_id')->nullable();
            $table->date('data')->nullable();
            $table->time('hora')->nullable();
            $table->string('course')->nullable();
            $table->integer('people')->nullable();
            $table->text('remark')->nullable();
            $table->timestamps();
            $table->integer('free')->nullable();
            $table->double('rate', 15, 2)->nullable();
            $table->double('total', 15, 2)->nullable();
            $table->double('ats_rate', 15, 2)->nullable();
            $table->double('ats_total_rate', 15, 2)->nullable();
            $table->double('profit', 15, 2)->nullable();
            $table->text('remark_internal')->nullable();

            $table->index(['id', 'pedido_produto_id'], 'pedido_games_id_IDX');
            $table->index(['data', 'hora'], 'pedido_games_data_IDX');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pedido_games');
    }
}