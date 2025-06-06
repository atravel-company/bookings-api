<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateValorTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('valor_tickets', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('pedido_produto_id')->nullable();
            $table->double('valor_ticket', 15, 2)->nullable();
            $table->double('valor_extra', 15, 2)->nullable();
            $table->decimal('kick', 8, 2)->nullable();
            $table->decimal('markup', 8, 2)->nullable();
            $table->double('total', 15, 2)->nullable();
            $table->double('profit', 15, 2)->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['id', 'pedido_produto_id'], 'valor_tickets_id_IDX');
            $table->index(['total', 'profit'], 'valor_tickets_total_IDX');
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
        Schema::dropIfExists('valor_tickets');
    }
}