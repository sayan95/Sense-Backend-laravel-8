<?php

namespace App\Notifications;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Lang;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ResetYourPassword extends Notification implements ShouldQueue
{
    use Queueable;
    private $token;
    
    public function __construct($token)
    {
        $this->token = $token;
    }

    
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $appUrl = config('app.therapist_reset_password_url');
        
        $url = URL::temporarySignedRoute(
            'therapist.verify.reset', 
            Carbon::now()->addMinutes(30),
            [
                'token' => $this->token,
                'email' => urlencode($notifiable->email)
            ]
        );
            
        $url = str_replace(url('/api/therapist/auth-api/reset-password'), $appUrl, $url);
        
        return (new MailMessage)
            ->subject(Lang::get('Reset Password '))
            ->line(Lang::get('You are receiving this email because we received a password reset request for your account.'))
            ->action(Lang::get('Reset Password'), $url)
            ->line(Lang::get('Note: This link is valid for only 30 miniutes'))
            ->line(Lang::get('If you did not request a password reset, no further action is required.'));
    }
}
