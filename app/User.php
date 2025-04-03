<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Backpack\Base\app\Notifications\ResetPasswordNotification as ResetPasswordNotification;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable {
    use SoftDeletes;
    use Notifiable;
    use HasRoles;

    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
    protected $fillable = [
        'name', 'email', 'password', 'social_denomination', 'path_image', 'fiscal_number', 'remarks', 'web', 'active'];

        /**
        * The attributes that should be hidden for arrays.
        *
        * @var array
        */
        protected $hidden = [
            'password', 'remember_token',
        ];

        protected $casts = ['active' => 'boolean'];

        /**
        * Send the password reset notification.
        *
        * @param  string  $token
        * @return void
        */

        public function sendPasswordResetNotification( $token ) {
            $this->notify( new ResetPasswordNotification( $token ) );
        }

        public function avaliacoes() {

            return $this->hasMany( 'App\AvaliacaoProduto' );
        }

        public function destination() {

            return $this->hasMany( 'App\UserBankAccountDestination' );
        }

        public function contact() {

            return $this->hasMany( 'App\UserContact' );
        }

        public function location() {

            return $this->hasMany( 'App\UserLocation' );
        }

        public function getPathImageAttribute( $value ) {

            if ( preg_match( '/_user/i', $value ) ) {
                $img = str_replace( '/storage/app/public/', '', $value );
                return $img;
            } else {
                //return asset( 'storage/'.$value );
                return $value;
            }
        }

    }
