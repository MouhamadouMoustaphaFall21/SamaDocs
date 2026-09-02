<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WelcomeMail extends Notification
{
    use Queueable;

    public function __construct(
        public User $user,
    ) {
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Bienvenue sur SamaDocs')
            ->greeting('Bonjour ' . trim($this->user->first_name ?: $this->user->name) . ' !')
            ->line('Votre compte SamaDocs vient d\'être créé avec succès.')
            ->line('Vous pouvez dès maintenant stocker, organiser et retrouver tous vos documents en un seul endroit.')
            ->action('Accéder à mon espace', url('/dashboard'))
            ->line('Si vous n\'avez pas créé ce compte, vous pouvez ignorer cet e-mail.')
            ->salutation('L\'équipe SamaDocs');
    }
}
