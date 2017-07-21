<?php

namespace Brackets\AdminAuth\Tests;

use Brackets\AdminAuth\Notifications\ResetPassword;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Brackets\AdminAuth\Auth\Activations\CanActivate;
use Brackets\AdminAuth\Contracts\Auth\CanActivate as CanActivateContract;

class TestStandardUserModel extends Authenticatable implements CanActivateContract
{
    use Notifiable;
    use CanActivate;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    //TODO move to generator?
    public function sendPasswordResetNotification($token)
    {
        $this->notify(app( ResetPassword::class, ['token' => $token]));
    }
}
