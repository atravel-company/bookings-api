<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePdfRoleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pdf_role', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('produto_pdf_id')->nullable();
            $table->unsignedInteger('role_id')->nullable();
            $table->timestamps();

            $table->index('produto_pdf_id');
            $table->index('role_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pdf_role');
    }
}