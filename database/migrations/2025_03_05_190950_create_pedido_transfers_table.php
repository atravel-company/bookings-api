<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePedidoTransfersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pedido_transfers', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('pedido_produto_id')->nullable();
            $table->integer('transfergest_id')->nullable()->comment('salvo ao enviar para api TG');
            $table->integer('transfergest_booking')->nullable();
            $table->date('data')->nullable();
            $table->time('hora')->nullable();
            $table->integer('adult')->default(0);
            $table->integer('children')->default(0);
            $table->integer('babie')->default(0);
            $table->string('flight')->nullable();
            $table->string('pickup')->nullable();
            $table->string('dropoff')->nullable();
            $table->text('remark')->nullable();
            $table->timestamps();
            $table->string('company')->nullable();
            $table->double('total', 15, 2)->default(0.00);
            $table->double('ats_rate', 15, 2)->default(0.00);
            $table->double('profit', 15, 2)->default(0.00);
            $table->text('remark_internal')->nullable();

            $table->index(['id', 'pedido_produto_id'], 'pedido_transfers_id_IDX');
            $table->index(['transfergest_id', 'transfergest_booking'], 'pedido_transfers_transfergest_id_IDX');
            $table->index(['data', 'hora'], 'pedido_transfers_data_IDX');
            $table->index('pedido_produto_id');
            $table->index('transfergest_id', 'servico_transfergest_id');
            $table->index('transfergest_booking');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pedido_transfers');
    }
}