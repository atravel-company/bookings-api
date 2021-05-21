<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SupplierBankAccountDestination extends Model
{
    //
    protected $fillable = ['supplier_id','destination'];

    public function supplier()
	{
		return $this->belongsTo('App\Supplier');
	}

	public function account(){

		return $this->hasMany('App\SupplierBankAccount', 'supp_bank_acc_dest_id');
	}
}
