<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ContactNotification extends Notification
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
            'name' => $this->data['name'] ?? 'User',
            'title' => $this->data['title'],
            'email' => $this->data['email'],
            'type' => $this->data['type'],
            'message' => $this->data['description'],
        ];
    }
}
