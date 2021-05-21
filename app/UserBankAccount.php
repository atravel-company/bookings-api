<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserBankAccount extends Model
{
    //

	protected $fillable = ['usr_bank_acc_dest_id','type', 'account_number'];

    public function userbankaccountdestination()
	{
		return $this->belongsTo('App\UserBankAccountDestination');
	}

}
