<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProdutosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('produtos', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('nome');
            $table->text('descricao');
            $table->boolean('estado')->default(1);
            $table->unsignedInteger('destino_id')->nullable();
            $table->unsignedInteger('supplier_id')->nullable();
            $table->boolean('alojamento')->nullable();
            $table->boolean('golf')->nullable();
            $table->boolean('transfer')->nullable();
            $table->boolean('car')->nullable();
            $table->boolean('ticket')->nullable();
            $table->string('email')->nullable();
            $table->string('email_type')->nullable();
            $table->softDeletes();

            $table->index('destino_id');
            $table->index('supplier_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('produtos');
    }
}