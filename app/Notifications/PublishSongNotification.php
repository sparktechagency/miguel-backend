<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PublishSongNotification extends Notification
{
    use Queueable;
 public $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function via($notifiable)
    {
        return ['database']; // You can add other channels (like 'mail') if needed
    }

    public function toDatabase($notifiable)
    {
        return [
            'id' => $this->data['id'],
            'name' => $this->data['full_name'] ?? 'User',
            'profile' => $this->data['profile'],
            'email' => $this->data['email'],
            'message' => "New song have been published.",
        ];
    }
}
