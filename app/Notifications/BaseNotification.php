<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

abstract class BaseNotification extends Notification
{
    use Queueable;

    public function via($notifiable)
    {
        return ['database']; // toutes les notif stockées en DB
    }
}
