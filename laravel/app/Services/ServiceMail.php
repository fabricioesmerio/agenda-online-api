<?php

namespace App\Services;

use Illuminate\Support\Facades\Mail;
use App\Mail\SimpleMail;

class EmailService
{
    public function send(string $to, string $body): void
    {
        Mail::to($to)->send(new SimpleMail($body));
    }
}