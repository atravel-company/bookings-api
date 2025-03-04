<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserBankAccountDestination extends Model
{
    //

    protected $fillable = ['user_id','destination'];

    public function user()
	{
		return $this->belongsTo('App\User');
	}

	public function account(){

		return $this->hasMany('App\UserBankAccount', 'usr_bank_acc_dest_id');
	}

}
