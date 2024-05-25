<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Expo\ExpoChannel;
use NotificationChannels\Expo\ExpoMessage;

class ReservationCanceled extends Notification
{
    use Queueable;

    // private Message $message;

    // public function __construct(Message $message)
    // {
    //     $this->message = $message;
    // }

    public function __construct()
    {
    }

    public function via($notifiable)
    {
        return [ExpoChannel::class];
    }

    public function toExpo($notifiable)
    {
        return ExpoMessage::create()
            ->title("Eazylife")
            ->body('Your reservation has been canceled');
        // ->badge(1);
    }
}
