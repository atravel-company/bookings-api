<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCustomFieldsToUsersTable extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('social_denomination')->nullable();
            $table->string('fiscal_number')->nullable();
            $table->string('web')->nullable();
            $table->text('remarks')->nullable();
            $table->string('path_image')->nullable();
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('social_denomination');
            $table->dropColumn('fiscal_number');
            $table->dropColumn('web');
            $table->dropColumn('remarks');
            $table->dropColumn('path_image');
        });
    }
}
