<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PurchageNotification extends Notification
{
    use Queueable;

    public $order;
    public $user;

    public function __construct($order,$user)
    {
        $this->order = $order;
        $this->user = $user;

    }

    public function via($notifiable)
    {
        return ['database']; // You can also add 'mail', 'slack', etc.
    }

    public function toDatabase($notifiable)
    {
        return [
            'id' => $this->user->id,
            'order_id' => $this->order->id,
            'order_number' => $this->order->order_number,
            'total_amount' => $this->order->total_amount,
            'message' => "A new order has been placed. Order Number: {$this->order->order_number}.",
        ];
    }
}
