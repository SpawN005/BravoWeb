<?php
// src/Service/VerificationService.php

namespace App\Service;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use libphonenumber\PhoneNumberUtil;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\NumberParseException;
use Twilio\Rest\Client;

class VerificationService
{
    private $twilioClient;
    private $twilioNumber;

    public function __construct(Client $twilioClient, string $twilioNumber)
    {
        $this->twilioClient = $twilioClient;
        $this->twilioNumber = $twilioNumber;
    }

    public function sendVerificationCode(Request $request): JsonResponse
    {
        // Extract the phone number from the form submission
        $phoneNumber = $request->request->get('phone');

        // Check whether the phone number already contains the country code
        $phoneUtil = PhoneNumberUtil::getInstance();
        try {
            $numberProto = $phoneUtil->parse($phoneNumber, null);
            if (!$phoneUtil->isValidNumber($numberProto)) {
                throw new NumberParseException(
                    NumberParseException::NOT_A_NUMBER,
                    'Invalid phone number'
                );
            }
            if ($phoneUtil->getRegionCodeForNumber($numberProto) != 'TN') {
                throw new NumberParseException(
                    NumberParseException::INVALID_COUNTRY_CODE,
                    'Invalid country code'
                );
            }
        } catch (NumberParseException $e) {
            // Handle the exception if the phone number is not valid
            return new JsonResponse(['success' => false, 'message' => $e->getMessage()]);
        }

        // Generate a verification code and save it in the user's session
        $verificationCode = rand(100000, 999999);
        $request->getSession()->set('verification_code', $verificationCode);

        // Send the verification code via SMS using Twilio
        try {
            $message = $this->twilioClient->messages->create(
                '+' . $phoneNumber,
                [
                    'from' => $this->twilioNumber,
                    'body' => "Your verification code is: $verificationCode",
                ]
            );
        } catch (\Exception $e) {
            // Handle the exception if the SMS could not be sent
            return new JsonResponse(['success' => false, 'message' => 'Failed to send verification code']);
        }

        // Return a JSON response indicating success
        return new JsonResponse(['success' => true]);
    }
}
