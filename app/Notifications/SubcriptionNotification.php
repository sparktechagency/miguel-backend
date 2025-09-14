<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SubcriptionNotification extends Notification
{
    use Queueable;

  public $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function via($notifiable)
    {
        return ['database']; 
    }

    public function toDatabase($notifiable)
    {
        $name = $this->data['name'] ?? 'User';
        $message = "$name has subscribed to your website.";

        return [
            'name' => $name,
            'email' => $this->data['email'],
            'message' => $message,
        ];
    }
}
