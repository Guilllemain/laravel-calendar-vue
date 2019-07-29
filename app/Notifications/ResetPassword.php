<?php

namespace App\Notifications;

use Illuminate\Support\Facades\Lang;
use Illuminate\Auth\Notifications\ResetPassword as ResetPasswordNotification;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPassword extends ResetPasswordNotification
{
    /**
     * Build the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        if (static::$toMailCallback) {
            return call_user_func(static::$toMailCallback, $notifiable, $this->token);
        }

        return (new MailMessage)
            ->greeting('Bonjour !')
            ->subject(Lang::getFromJson('Réinitialisation mot de passe'))
            ->line(Lang::getFromJson('Cliquez sur le bouton ci-dessous pour réinitialiser votre mot de passe.'))
            ->action(Lang::getFromJson('Réinitialiser mon mot de passe'), url(config('app.url') . route('password.reset', ['token' => $this->token, 'email' => $notifiable->getEmailForPasswordReset()], false)))
            ->line(Lang::getFromJson('Ce lien expirera dans :count minutes.', ['count' => config('auth.passwords.users.expire')]))
            ->line(Lang::getFromJson('Si vous ne souhaitez pas modifier votre mot de passe, vous pouvez supprimer cet email.'));
    }
}
