<?php

namespace App\MessageHandler;

use App\Message\SendNotification;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class SendNotificationHandler implements MessageHandlerInterface

{
    private $mailer;

    public function __construct(MailerInterface $mailer,)
    {
        $this->mailer = $mailer;
    }

    public function __invoke(
        SendNotification $notification
    ):void
    {
        $template = $notification->getTemplate();
        $context = $notification->getContext();
        $email = (new TemplatedEmail())
        ->from($notification->getFrom())
        ->to($notification->getTo())
        ->subject($notification->getSubject())
        ->htmlTemplate("emails/$template.html.twig")
        ->context($context);

        // On envoi le mail

        $this->mailer->send($email);

    }
}