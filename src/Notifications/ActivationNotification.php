<?php

namespace Brackets\AdminAuth\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ActivationNotification extends Notification
{
    /**
     * The password reset token.
     *
     * @var string
     */
    public $token;

    /**
     * Create a notification instance.
     *
     * @param string $token
     * @return void
     */
    public function __construct(string $token)
    {
        $this->token = $token;
    }

    /**
     * Get the notification's channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via(mixed $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Build the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return MailMessage
     */
    public function toMail(mixed $notifiable): MailMessage
    {
        //TODO change to template?
        return (new MailMessage)
            ->line(trans('brackets/admin-auth::activations.email.line'))
            ->action(trans('brackets/admin-auth::activations.email.action'), route('brackets/admin-auth::admin/activation/activate', $this->token))
            ->line(trans('brackets/admin-auth::activations.email.notRequested'));
    }
}
