<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserBankAccountDestinationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_bank_account_destinations', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->nullable();
            $table->string('destination');
            $table->timestamps();

            $table->index('user_id');
            // Foreign key constraint should be added in a separate Schema::table if 'users' table migration is not yet run.
            // Considering 'users' table is being redefined in this batch, add FK later in another migration or after users migration.
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_bank_account_destinations');
    }
}