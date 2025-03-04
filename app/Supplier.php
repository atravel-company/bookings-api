<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supplier extends Model
{
		use SoftDeletes;

    //
    protected $fillable = ['name','social_denomination', 'path_image', 'fiscal_number', 'remarks', 'web'];

    public function destination(){

		return $this->hasMany('App\SupplierBankAccountDestination');
	}

	 public function contact(){

		return $this->hasMany('App\SupplierContact');
	}

	public function location(){

		return $this->hasMany('App\SupplierLocation');
	}

	protected $table = 'suppliers';

}