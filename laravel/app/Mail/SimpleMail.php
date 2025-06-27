<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SimpleMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $body;

    /**
     * Create a new message instance.
     */
    public function __construct(string $body)
    {
        $this->body = $body;
    }

    public function build()
    {
        return $this->subject('Assunto do Email')
                    ->view('emails.simple')
                    ->with(['body' => $this->body]);
    }
}
