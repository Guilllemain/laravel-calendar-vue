<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Notifications\ResetPassword as ResetPasswordNotification;
use App\Notifications\VerifyEmail as VerifyEmailNotification;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'firstname', 'lastname', 'email', 'password', 'api_token', 'email_verified_at'
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
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    
    protected $appends = ['fullname', 'isAdmin'];

    public function isAdmin()
    {
        return $this->email === 'yguillemain@fft.fr';
    }

    public function reservations()
    {
        return $this->hasMany('App\Reservation');
    }

    public function getIsAdminAttribute()
    {
        return $this->email === 'fkmit@fft.fr' || $this->email === 'admin@admin.com';
    }

    public function getFullnameAttribute()
    {
        $fullname = strtolower($this->firstname . ' ' . $this->lastname);

        if (strpos($fullname, '-') !== false) {
            $fullname = str_replace('-', ' ', $fullname);
        }
        return ucwords($fullname);
    }

    //overwrite the sendEmailVerificationNotification method
    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyEmailNotification);
    }

    //overwrite the sendPasswordResetNotification method
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }
}
