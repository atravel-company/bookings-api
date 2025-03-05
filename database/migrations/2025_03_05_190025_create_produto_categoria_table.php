<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProdutoCategoriaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('produto_categoria', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('produto_id')->nullable();
            $table->unsignedInteger('categoria_id')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('produto_id');
            $table->index('categoria_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('produto_categoria');
    }
}