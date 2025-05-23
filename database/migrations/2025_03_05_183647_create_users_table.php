<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('users'); // Drop existing table if it exists - for replacement

        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->boolean('active')->default(true);
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
            $table->string('vat')->nullable();
            $table->text('remarks')->nullable();
            $table->string('fiscal_number')->nullable();
            $table->string('web')->nullable();
            $table->string('social_denomination')->nullable();
            $table->string('path_image')->nullable();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}