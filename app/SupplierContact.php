<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SupplierContact extends Model
{
    //
    protected $fillable = ['supplier_id','type', 'name', 'telephone', 'mobile', 'email'];

    public function supplier()
	{
		return $this->belongsTo('App\Supplier');
	}
}
