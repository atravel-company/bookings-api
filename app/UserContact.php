<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserContact extends Model
{
   	use SoftDeletes;

    protected $fillable = ['user_id','type', 'name', 'telephone', 'mobile', 'email'];

    public function user()
	{
		return $this->belongsTo('App\User');
	}
}
