<?php

namespace App\Email;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;

class ContactEmail extends TemplatedEmail
{
    public function __construct($subject, $template)
    {
        parent::__construct();

        $this->subject($subject)
            ->htmlTemplate($template)
            ->from('ahmedaziz.rebhi@esprit.tn')
            ->to('admin@example.com');
    }

    public function setRecipient($recipient)
    {
        $this->to($recipient);

        return $this;
    }
}
