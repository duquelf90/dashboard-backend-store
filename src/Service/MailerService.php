<?php

namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mailer\Transport\TransportInterface;
use Symfony\Component\Mime\Email;


class MailerService
{
    public function __construct(private readonly TransportInterface $mailer)
    {
    }

    public function sendEmail(string $to, string $subject, string $body): void
    {
        $message = (new Email())
            ->from('no-reply@example.com')
            ->to($to)
            ->subject($subject)
            ->html($body, );

        $this->mailer->send($message);
    }
}