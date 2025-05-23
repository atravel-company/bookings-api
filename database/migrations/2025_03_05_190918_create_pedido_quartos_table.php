<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePedidoQuartosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pedido_quartos', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('pedido_produto_id')->nullable();
            $table->date('checkin')->nullable();
            $table->date('checkout')->nullable();
            $table->string('type')->nullable();
            $table->decimal('rooms', 8, 1)->nullable();
            $table->string('plan')->nullable();
            $table->integer('people')->nullable();
            $table->timestamps();
            $table->text('remark')->nullable();
            $table->double('night', 15, 2)->nullable();
            $table->string('offer_name')->nullable();
            $table->double('offer', 15, 2)->nullable();
            $table->double('price', 15, 2)->nullable();
            $table->double('total', 15, 2)->nullable();
            $table->double('ats_rate', 15, 2)->nullable();
            $table->double('ats_total_rate', 15, 2)->nullable();
            $table->double('profit', 15, 2)->nullable();
            $table->integer('days')->nullable();
            $table->text('remark_internal')->nullable();

            $table->index(['id', 'pedido_produto_id'], 'pedido_quartos_id_IDX');
            $table->index(['checkin', 'checkout'], 'pedido_quartos_checkin_IDX');
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
        Schema::dropIfExists('pedido_quartos');
    }
}