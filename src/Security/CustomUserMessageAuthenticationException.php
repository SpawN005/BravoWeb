<?php

namespace App\Security;

use Symfony\Component\Security\Core\Exception\AuthenticationException;

class CustomUserMessageAuthenticationException extends AuthenticationException
{
    private $messageKey;
    private $messageData = [];

    public function __construct(string $messageKey, array $messageData = [])
    {
        $this->messageKey = $messageKey;
        $this->messageData = $messageData;

        parent::__construct('');
    }

    public function getMessageKey()
    {
        return $this->messageKey;
    }

    public function getMessageData()
    {
        return $this->messageData;
    }
}
