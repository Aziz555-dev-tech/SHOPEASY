<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TemporaryPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    public $passwordTemp;

    public function __construct($passwordTemp)
    {
        $this->passwordTemp = $passwordTemp;
    }

    public function build()
    {
        return $this->subject('Votre mot de passe temporaire')
                    ->view('emails.temporary-password');
    }
}
