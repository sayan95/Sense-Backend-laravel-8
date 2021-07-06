<?php

namespace App\Models\Users;

use Tymon\JWTAuth\Contracts\JWTSubject;
use App\Notifications\ResetYourPassword;
use Illuminate\Notifications\Notifiable;
use App\Notifications\LoginOTPNotification;
use App\Notifications\WelcomeNotification;
use Rennokki\QueryCache\Traits\QueryCacheable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Therapist extends Authenticatable implements JWTSubject
{
    use Notifiable;
    use QueryCacheable;

    // cache queries for 3 minutes
    protected $cacheFor = 180; 
    protected static $flushCacheOnUpdate = true;
    
    // fillable properties
    protected $fillable = [
        'id','username','email' , 'password', 
        'user_type', 'is_puiblished', 'email_verified_at', 'is_active', 
        'profile_created', 'logged_in_at', 'password_reset_initiated', 'password_reset_at',
    ];
    
    // date fields
    protected $dates = [
        'created_at', 'updated_at', 'email_verified_at', 
        'logged_in_at', 'password_reset_at'
    ];

    // relationships
    public function profile(){
        return $this->hasOne(TherapistProfile::class);
    }

    // jwt specific methods
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    public function getJWTCustomClaims()
    {
        return [];
    }

    // helpers
    // checks if the user has a verified email
    public function hasVerifiedEmail(){
        return $this->is_active;
    }

    // sends verification link
    public function sendEmailVerificationMail($token){
        $this->notify(new LoginOTPNotification($token));
    }

    // sends welcome mail
    public function sendWelcomeMail(){
        $this->notify(new WelcomeNotification);
    }

    // send password reset link
    public function sendPasswordResetMail($token){
        $this->notify(new ResetYourPassword($token));
    }
}
