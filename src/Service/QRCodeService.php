<?php

namespace App\Service;

use App\Entity\Reservation;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

class QRCodeService
{
    public function generateQRCodeContent(Reservation $reservation)
    {
        $qrCodeContent = '';

        $qrCodeContent .= 'BEGIN:VEVENT' . PHP_EOL;
        $qrCodeContent .= 'SUMMARY:' . $reservation->getIdEvent()->getTitle() . PHP_EOL;
        $qrCodeContent .= 'DTSTART:' . date_format($reservation->getIdEvent()->getDateBeg(), 'Ymd\THis\Z') . PHP_EOL;
        $qrCodeContent .= 'DTEND:' . date_format($reservation->getIdEvent()->getDateEnd(), 'Ymd\THis\Z') . PHP_EOL;
        $qrCodeContent .= 'END:VEVENT';

        return $qrCodeContent;
    }

    public function generateQRCodeImage(Reservation $reservation)
    {
        $qrCodeContent = $this->generateQRCodeContent($reservation);

        $qrCode = new QrCode($qrCodeContent);

        $qrCode->setSize(500);
        $qrCode->setForegroundColor(new Color(255, 0, 0));
        $qrCode->setBackgroundColor(new Color(255, 255, 255));
        $qrCode->setEncoding(new Encoding('UTF-8'));
        $qrCode->setErrorCorrectionLevel(new ErrorCorrectionLevelHigh());

        $writer = new PngWriter();
        $qrCodeImage = $writer->write($qrCode);

        return $qrCodeImage;
    }

    /**
     * Fonction pour envoyer un email de confirmation avec un code QR pour une réservation
     */
    public function sendConfirmationEmail(Reservation $reservation, $recipientEmail, $senderEmail, $senderName, $session) {
        // Configuration de l'e-mail
        $subject = 'Confirmation de réservation';
        $body = 'Bonjour ' . $reservation->getIdParticipant()->getEmail() . ',<br><br>Votre réservation pour l\'événement "'
            . $reservation->getIdEvent()->getTitle() . '" a été confirmée.<br><br>Voici votre code QR pour accéder à l\'événement:<br><br>';

        // Générer le code QR pour la réservation
        $qrCodeImage = $this->generateQRCodeImage($reservation);

        // Envoyer l'e-mail avec le code QR en pièce jointe
        $this->sendEmailWithQRCode($recipientEmail, $senderEmail, $senderName, $subject, $body, $qrCodeImage, $session);
    }
}
