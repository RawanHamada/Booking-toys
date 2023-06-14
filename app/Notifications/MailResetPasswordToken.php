<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MailResetPasswordToken extends Notification
{
    use Queueable;

    public $token;
    public $code;

    /**
     * Create a new notification instance.
     */
    public function __construct($token, $code)
    {
        $this->token = $token;
        $this->code = $code;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
        ->subject('Reset Password Email....')
                    ->line('The verification code is:' . $this->code)
                    ->action('Notification Action', "http://localhost/reset-password?token=$this->token")
                    ->line('Thank you for using our application!');
    }
}
