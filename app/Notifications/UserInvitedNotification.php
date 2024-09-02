<?php

namespace App\Notifications;

use App\Models\Projet;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserInvitedNotification extends Notification
{
    use Queueable;

    public $projet;
    public $inviter; 

    /**
     * Create a new notification instance.
     */
    public function __construct(Projet $projet, $inviter)
    {
        $this->projet = $projet;
        $this->inviter = $inviter;
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
            ->greeting('Bonjour ' . $notifiable->nom . ' ' .$notifiable->prenom . ',')
            ->line(auth()->user()->nom . ' '. auth()->user()->prenom . ' vous a envoyé une invitation à rejoindre le projet "' . $this->projet->nom . '".')
            ->line('Voulez-vous rejoindre ce projet ?')
            ->action('Accepter l\'invitation', url('/invitations/' . $this->inviter->id . '/accept'))
            ->line('Si vous ne souhaitez pas rejoindre ce projet, vous pouvez décliner l\'invitation en utilisant le lien suivant :')
            ->line('<a href="' . url('/invitations/' . $this->inviter->id . '/decline') . '">Décliner l\'invitation</a>')
            ->line('Merci d\'utiliser notre application !')
            ->salutation('Cordialement, L\'équipe de gestion de projet');
    }
    
}
