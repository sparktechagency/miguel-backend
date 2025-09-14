<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ApplyArtistNotification extends Notification
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
            'name' => $this->data['name'],
            'email' => $this->data['email'],
            'message' => $this->data['name'] . ' has applied for the artist.',
            'created_at' => now(),
        ];
    }
}
