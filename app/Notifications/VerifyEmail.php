<?php

namespace App\Notifications;

use Illuminate\Support\Facades\Lang;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Auth\Notifications\VerifyEmail as VerifyEmailNotification;

class VerifyEmail extends VerifyEmailNotification
{
    /**
     * Build the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $verificationUrl = $this->verificationUrl($notifiable);

        if (static::$toMailCallback) {
            return call_user_func(static::$toMailCallback, $notifiable, $verificationUrl);
        }

        return (new MailMessage)
            ->greeting('Bonjour !')
            ->subject(Lang::getFromJson('Vérification adresse email'))
            ->line(Lang::getFromJson('Merci de cliquer sur le bouton ci-dessous pour valider votre adresse email.'))
            ->action(Lang::getFromJson('Vérifier mon email'), $verificationUrl)
            ->line(Lang::getFromJson("Si vous n'êtes pas à l'origine de cette demande, vous pouvez supprimer cet email."));
    }
}
