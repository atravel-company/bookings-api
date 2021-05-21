<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SupplierLocation extends Model
{
	use SoftDeletes;

    //
	protected $fillable = ['supplier_id','location','address', 'zip_code', 'city', 'country'];

	public function supplier()
	{
		return $this->belongsTo('App\Supplier');
	}
}
