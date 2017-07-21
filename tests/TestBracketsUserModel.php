<?php

namespace Brackets\AdminAuth\Tests;

use Brackets\AdminAuth\Auth\Activations\CanActivate;
use Brackets\AdminAuth\Contracts\Auth\CanActivate as CanActivateContract;
use Brackets\AdminAuth\Notifications\ResetPassword;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class TestBracketsUserModel extends Authenticatable implements CanActivateContract
{
    use Notifiable;
    use CanActivate;
    use SoftDeletes;


    protected $fillable = [
        "email",
        "password",
        "first_name",
        "last_name",
        "activated",
        "forbidden",

    ];

    protected $hidden = [
        "password",
        "remember_token",

    ];

    protected $dates = [
        "created_at",
        "updated_at",
        "deleted_at",

    ];



    /**
     * Send the password reset notification.
     *
     * @param    string  $token
     * @return  void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(app( ResetPassword::class, ['token' => $token]));
    }
}
