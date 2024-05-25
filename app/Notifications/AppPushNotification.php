<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;
use NotificationChannels\Expo\ExpoChannel;
use NotificationChannels\Expo\ExpoMessage;

class AppPushNotification extends Notification
{
    use Queueable;

    public $title;
    public $body;

    /**
     * Create a new notification instance.
     */
    public function __construct($title, $body)
    {
        $this->title = $title;
        $this->body = $body;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return [ExpoChannel::class];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toExpo($notifiable)
    {
        return ExpoMessage::create()
            ->title($this->title)
            ->body($this->body);
    }
}
