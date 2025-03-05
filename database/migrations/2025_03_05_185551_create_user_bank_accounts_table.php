<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserBankAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_bank_accounts', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('usr_bank_acc_dest_id')->nullable();
            $table->string('type');
            $table->string('account_number');
            $table->timestamps();

            $table->index('usr_bank_acc_dest_id');
            // Foreign key constraint should be added in a separate Schema::table after 'user_bank_account_destinations' migration.
            // Considering 'user_bank_account_destinations' table is being created in this batch, add FK later or in another migration.
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_bank_accounts');
    }
}