<?php

namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Address;
use App\Entity\Reservation;

class EmailService
{
    private $mailer;
    private $qrCodeService;

    public function __construct(MailerInterface $mailer, QRCodeService $qrCodeService)
    {
        $this->mailer = $mailer;
        $this->qrCodeService = $qrCodeService;
    }
    
    /**
     * Fonction pour envoyer un email de confirmation avec un code QR pour une réservation
     */
    public function sendConfirmationEmail(Reservation $reservation, $recipientEmail, $senderEmail, $senderName, $session) {
        // Configuration de l'e-mail
        $subject = 'Confirmation de réservation';
        $body = 'Bonjour ' . $reservation->getIdParticipant()->getEmail() . ',<br><br>Votre réservation pour l\'événement "'
            . $reservation->getIdEvent()->getTitle() . '" a été confirmée.<br><br>Voici votre code QR pour accéder à l\'événement:<br><br>';

        // // Générer le code QR pour la réservation
        // $qrCodeImage = $this->qrCodeService->generateQRCodeImage($reservation);

        // // Envoyer l'e-mail avec le code QR en pièce jointe
        // $this->sendEmailWithQRCode($recipientEmail, $senderEmail, $senderName, $subject, $body, $qrCodeImage, $session);
    }

    /**
     * Fonction pour envoyer un email avec un code QR en pièce jointe
     */
    public function sendEmailWithQRCode($recipientEmail, $senderEmail, $senderName, $subject, $body, $session) {
        // Créer l'objet Email
        $email = (new Email())
            ->from(new Address($senderEmail, $senderName))
            ->to($recipientEmail)
            ->subject($subject)
            ->html($body);

        // // Ajouter le code QR à l'e-mail en tant que pièce jointe
        // $email->attach($qrCodeImage, 'qr-code.png', 'image/png');

        // Envoyer l'e-mail
        $this->mailer->send($email);

        $message = 'L\'e-mail de confirmation a été envoyé avec succès.';
        $flashBag = $session->getFlashBag();
        $flashBag->add('success', $message);
    }
}
