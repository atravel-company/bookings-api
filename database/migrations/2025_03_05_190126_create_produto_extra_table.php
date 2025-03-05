<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProdutoExtraTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('produto_extra', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('produto_id')->nullable();
            $table->unsignedInteger('extra_id')->nullable();
            $table->string('formulario')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['produto_id', 'extra_id'], 'produto_extra_produto_id_extra_id_unique');
            $table->index('extra_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('produto_extra');
    }
}