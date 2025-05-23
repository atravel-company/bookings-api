<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserBankAccountsForeignKeys extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_bank_accounts', function (Blueprint $table) {
            $table->foreign('usr_bank_acc_dest_id')->references('id')->on('user_bank_account_destinations')->onDelete('set null'); // Adjust onDelete as needed
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_bank_accounts', function (Blueprint $table) {
            $table->dropForeign(['usr_bank_acc_dest_id']);
        });
    }
}