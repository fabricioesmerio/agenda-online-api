<?php

namespace App\Services;

use MailerSend\MailerSend;
use MailerSend\Helpers\Builder\EmailParams;
use Illuminate\Support\Facades\Log;
use MailerSend\Helpers\Builder\Recipient;
use Illuminate\Support\Facades\File;

class MailerSendService
{
    private MailerSend $mailerSend;

    public function __construct()
    {
        $this->mailerSend = new MailerSend([
            'api_key' => config('services.mailersend.api_key'),
        ]);
    }

    public function sendEmail(string $toEmail, string $toName, string $subject, string $html)
    {
        try {

            $fromEmail = config('mail.from.address');
            $fromName = config('mail.from.name');


            $recipients = [
                new Recipient($toEmail, $toName),
            ];
            $emailParams = (new EmailParams())
                ->setFrom($fromEmail)
                ->setFromName($fromName)
                ->setRecipients($recipients)
                ->setSubject($subject)
                ->setHtml($html);

            $this->mailerSend->email->send($emailParams);
        } catch (\Exception $e) {
            Log::error('Erro ao enviar e-mail: ' . $e->getMessage());
            throw $e;
        }
    }

    public function renderTemplate(string $path, array $variables): string
    {
        $html = File::get(resource_path($path));

        foreach ($variables as $key => $value) {
            $html = str_replace('{{' . $key . '}}', $value, $html);
        }

        return $html;
    }
}
