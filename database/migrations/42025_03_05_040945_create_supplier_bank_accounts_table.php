<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSupplierBankAccountsTable extends Migration
{
    public function up()
    {
        Schema::create('supplier_bank_accounts', function (Blueprint $table) {
            $table->bigIncrements('id');
            // This foreign key points to the destination record.
            $table->unsignedBigInteger('supp_bank_acc_dest_id');
            $table->string('type')->nullable();
            $table->string('account_number')->nullable();
            $table->timestamps();

            $table->foreign('supp_bank_acc_dest_id')
                  ->references('id')->on('supplier_bank_account_destinations')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('supplier_bank_accounts');
    }
}
