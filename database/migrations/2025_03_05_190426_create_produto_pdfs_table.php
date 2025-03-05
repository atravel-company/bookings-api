<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProdutoPdfsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('produto_pdfs', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('produto_id')->nullable();
            $table->string('title');
            $table->text('description');
            $table->string('type');
            $table->string('path_image');
            $table->timestamps();
            $table->softDeletes();

            $table->index('produto_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('produto_pdfs');
    }
}