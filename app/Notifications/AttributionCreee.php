<?php 

namespace App\Notifications;

use App\Models\Attribution;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AttributionCreee extends Notification implements ShouldQueue
{
    use Queueable;

    protected $attribution;

    /**
     * Constructor
     */
    public function __construct(Attribution $attribution)
    {
        $this->attribution = $attribution;
    }

    /**
     * Channels used
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    /**
     * EMAIL notification
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Nouvelle attribution créée')
            ->greeting('Bonjour ' . $notifiable->name)
            ->line("Une nouvelle attribution vient d'être enregistrée.")
            ->line("Bien attribué : " . $this->attribution->bien->titre)
            ->line("Client : " . $this->attribution->client->name . ' ' . $this->attribution->client->surname)
            ->line("Montant à payer : " . number_format($this->attribution->prix, 0, ',', ' ') . " FCFA")
            ->line("Date d’attribution : " . $this->attribution->date_attribution->format('d/m/Y'))
            ->action('Voir l’attribution', url('/admin/attributions/' . $this->attribution->id))
            ->line('Merci pour votre confiance.');
    }

    /**
     * DATABASE notification
     */
    public function toDatabase($notifiable)
    {
        return [
            'attribution_id' => $this->attribution->id,
            'bien'           => $this->attribution->bien->titre,
            'client'         => $this->attribution->client->name . ' ' . $this->attribution->client->surname,
            'prix'           => $this->attribution->prix,
            'date_attribution' => $this->attribution->date_attribution,
            'message'        => "Nouvelle attribution du bien « " 
                                . $this->attribution->bien->titre 
                                . " » au client " 
                                . $this->attribution->client->name 
                                . ' ' 
                                . $this->attribution->client->surname
        ];
    }
}
