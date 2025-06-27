<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ForgotPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public string $token) {}

    public function build()
    {
        $url = "https://tuagenda.com.br/change-password/{$this->token}";

        return $this->subject('Redefinição de senha')
            ->view('emails.forgot-password')
            ->with(['resetLink' => $url]);
    }
}
