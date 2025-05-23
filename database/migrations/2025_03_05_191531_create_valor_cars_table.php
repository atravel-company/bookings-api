<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateValorCarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('valor_cars', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('pedido_produto_id')->nullable();
            $table->double('valor_car', 15, 2)->nullable();
            $table->double('valor_extra', 15, 2)->nullable();
            $table->decimal('kick', 8, 2)->nullable();
            $table->decimal('markup', 8, 2)->nullable();
            $table->double('total', 15, 2)->nullable();
            $table->double('profit', 15, 2)->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['id', 'pedido_produto_id'], 'valor_cars_id_IDX');
            $table->index(['total', 'profit', 'valor_car'], 'valor_cars_total_IDX');
            $table->index('pedido_produto_id');
            // Foreign key constraint should be added in a separate Schema::table after 'pedido_produto' migration.
            // Considering 'pedido_produto' table is already created in previous response, add FK later or in another migration.
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('valor_cars');
    }
}