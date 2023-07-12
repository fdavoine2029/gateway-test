<?php

namespace App\Message;

class SendNotification
{
    private $message;
    private $from;
    private $to;
    private $subject;
    private $template;
    private $context;

    public function __construct(
        
        string $from,
        string $to,
        string $subject,
        string $message, 
        string $template,
        array $context
        )
    {

        $this->from = $from;
        $this->to = $to;
        $this->subject = $subject;
        $this->message = $message;
        $this->template = $template;
        $this->context = $context;
    }

    
    public function getFrom()
    {
        return $this->from;
    }

    public function getTo()
    {
        return $this->to;
    }

    public function getSubject()
    {
        return $this->subject;
    }

    public function getTemplate()
    {
        return $this->template;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function getContext()
    {
        return $this->context;
    }

}