<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSupplierBankAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('supplier_bank_accounts', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('supp_bank_acc_dest_id')->nullable();
            $table->string('type');
            $table->string('account_number');
            $table->timestamps();

            $table->index('supp_bank_acc_dest_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('supplier_bank_accounts');
    }
}