<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePedidoGeralsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pedido_gerals', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type');
            $table->string('lead_name');
            $table->string('responsavel');
            $table->string('referencia');
            $table->timestamps();
            $table->unsignedInteger('user_id')->nullable();
            $table->double('valor', 15, 2)->nullable();
            $table->double('profit', 15, 2)->nullable();
            $table->string('status')->nullable();
            $table->softDeletes();

            $table->unique('id', 'pedido_gerals_id_IDX');
            $table->index(['lead_name', 'status'], 'pedido_gerals_lead_name_IDX');
            $table->index(['valor', 'profit'], 'pedido_gerals_valor_IDX');

            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pedido_gerals');
    }
}