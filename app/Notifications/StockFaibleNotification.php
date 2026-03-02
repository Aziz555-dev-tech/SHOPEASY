<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class StockFaibleNotification extends Notification
{
    public function __construct(public $biens)
    {}

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        $mail = (new MailMessage)
            ->subject('⚠️ Alerte Stock Faible')
            ->greeting('Bonjour ' . $notifiable->name)
            ->line('Les biens suivants ont un stock faible (≤ 5) :');

        foreach ($this->biens as $bien) {
            $mail->line("• {$bien->titre} — Stock : {$bien->stock}");
        }

        return $mail
            ->line('Veuillez réapprovisionner pour éviter les ruptures de stock probables.')
            ->salutation('— Shopeasy');
    }

    public function toArray($notifiable)
    {
        return [
            'type' => 'stock_faible',
            'biens' => $this->biens->map(fn($b) => [
                'id' => $b->id,
                'titre' => $b->titre,
                'stock' => $b->stock
            ])
        ];
    }
}
