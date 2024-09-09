<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ProjectInvitationNotification extends Notification
{
    use Queueable;

    public $project;
    public $task;

    public function __construct($project, $task = null)
    {
        $this->project = $project;
        $this->task = $task;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'type' => $this->task ? 'task' : 'invitation',
            'message' => $this->task 
                ? "Vous avez été assigné à une nouvelle tâche dans le projet {$this->project}."
                : "Vous avez été invité à rejoindre le projet {$this->project}.",
            'project' => $this->project,
            'task' => $this->task,
        ];
    }
}
