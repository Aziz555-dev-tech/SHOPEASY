<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TemporaryPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    public $passwordTemp;
    public $role;
    public $loginLink;

    public function __construct($passwordTemp, $role)
    {
        $this->passwordTemp = $passwordTemp;
        $this->role = $role;
        $this->loginLink = url('/'.$role.'/login');

    }

    public function build()
    {
        return $this->subject('Votre mot de passe temporaire')
                    ->view('emails.temporary-password');
    }
}
