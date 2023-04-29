<?php

namespace App\Entity;

use Twilio\Rest\Client;

class TwilioSmS
{
private $accountSid;
private $authToken;
private $from;

public function __construct($accountSid, $authToken, $from)
{
$this->accountSid = $accountSid;
$this->authToken = $authToken;
$this->from = $from;
}

public function sendSMS($to, $body)
{
$client = new Client($this->accountSid, $this->authToken);

$message = $client->messages->create(
$to,
array(
'from' => $this->from,
'body' => $body
)
);

return $message->sid;
}
}