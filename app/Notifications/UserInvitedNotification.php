<?php

namespace App\Notifications;

use App\Models\Projet;
use App\Models\Invitation;  // Assurez-vous d'importer la bonne classe Invitation
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserInvitedNotification extends Notification
{
    use Queueable;

    public $projet;
    public $task; 
    public $invitation;
    /**
     * Create a new notification instance.
     */
    public function __construct(Projet $projet, Invitation $invitation, $task = null)
    {
        $this->projet = $projet;
        $this->invitation = $invitation;
        $this->task = $task;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
    */

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $mailMessage = new MailMessage();

        if ($this->task) {
         
            return $mailMessage
                ->subject('Nouvelle tâche assignée dans le projet ' . $this->projet->name)
                ->greeting('Bonjour ' . $notifiable->name . ',')
                ->line('Vous avez été assigné à une nouvelle tâche dans le projet "' . $this->projet->nom . '".')
                ->line('Tâche : "' . $this->task . '"')
                ->action('Voir le projet', url('/projets/' . $this->projet->id));
        } else {
            // Notification pour une invitation à rejoindre un projet
            return $mailMessage
                ->subject('Invitation à rejoindre le projet ' . $this->projet->name)
                ->greeting('Bonjour ' . $notifiable->name . ',')
                ->line('Vous avez été invité à rejoindre le projet "' . $this->projet->nom . '".')
                ->action('Accepter l\'invitation', url('http://localhost:4200/notifications'));
        }
    }

    public function toArray(object $notifiable): array
    {
        if ($this->task !== null) {

            return [
                'type' => 'task',
                'projet_id' => $this->projet->id,
                'projet_name' => $this->projet->nom,
                'message' => 'Vous avez été assigné à une nouvelle tâche : "' . $this->task . '" dans le   projet "' . $this->projet->nom . '".',
                'task' => $this->task,
            ];
        } else {
            return [
                'type' => 'invitation',
            'projet_id' => $this->projet->id,
            'projet_name' => $this->projet->nom,
                'invitation' => $this->invitation,
                'message' => 'Vous avez été invité à rejoindre le projet "' . $this->projet->nom . '".',
            ];
        }
    }
}
