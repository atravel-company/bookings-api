<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserLocation extends Model
{
    use SoftDeletes;

    protected $fillable = ['user_id','location','address', 'zip_code', 'city', 'country'];

		public function user()
		{
			return $this->belongsTo('App\User');
		}

}
