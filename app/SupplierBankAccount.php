<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SupplierBankAccount extends Model
{
    //
    protected $fillable = ['supp_bank_acc_dest_id','type', 'account_number'];

    public function supplierbankaccountdestination()
	{
		return $this->belongsTo('App\SupplierBankAccountDestination');
	}

}
