<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddValorTransfersForeignKeys extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('valor_transfers', function (Blueprint $table) {
            $table->foreign('pedido_produto_id')->references('id')->on('pedido_produto')->onDelete('set null'); // Adjust onDelete as needed
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('valor_transfers', function (Blueprint $table) {
            $table->dropForeign(['pedido_produto_id']);
        });
    }
}