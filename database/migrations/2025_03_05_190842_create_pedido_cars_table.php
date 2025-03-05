<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePedidoCarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pedido_cars', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('pedido_produto_id')->nullable();
            $table->string('pickup')->nullable();
            $table->date('pickup_data')->nullable();
            $table->time('pickup_hora')->nullable();
            $table->string('pickup_flight')->nullable();
            $table->string('pickup_country')->nullable();
            $table->string('pickup_airport')->nullable();
            $table->string('dropoff')->nullable();
            $table->date('dropoff_data')->nullable();
            $table->time('dropoff_hora')->nullable();
            $table->string('dropoff_flight')->nullable();
            $table->string('dropoff_country')->nullable();
            $table->string('dropoff_airport')->nullable();
            $table->text('remark')->nullable();
            $table->timestamps();
            $table->string('group')->nullable();
            $table->string('model')->nullable();
            $table->double('rate', 15, 2)->nullable();
            $table->integer('days')->nullable();
            $table->double('tax', 15, 2)->nullable();
            $table->string('tax_type')->nullable();
            $table->double('total', 15, 2)->nullable();
            $table->double('ats_rate', 15, 2)->nullable();
            $table->double('ats_total_rate', 15, 2)->nullable();
            $table->double('profit', 15, 2)->nullable();
            $table->text('remark_internal')->nullable();

            $table->index(['pedido_produto_id', 'id'], 'pedido_cars_pedido_produto_id_IDX');
            $table->index(['pickup_data', 'pickup_hora'], 'pedido_cars_pickup_data_IDX');
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
        Schema::dropIfExists('pedido_cars');
    }
}