<?php

namespace App\Service;

use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Label\Margin\Margin;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\Builder\BuilderInterface;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;
use Endroid\QrCode\Label\Alignment\LabelAlignmentCenter;
use App\Repository\EventRepository;
use App\Entity\Reservation;
use App\Repository\ReservationRepository;

class QrcodeService
{
    /**
     * @var BuilderInterface
     */
    protected $builder;
    private $reservationRepository;
    

    public function __construct(BuilderInterface $builder, ReservationRepository $reservationRepository)
    {
        $this->reservationRepository = $reservationRepository;
      
        $this->builder = $builder;
        
    }
    // public function getEventInfoString(Reservation $reservation)
    //  {
    
    //     $qrCodeContent = '';

    //     $event = $reservation->getIdEvent();
    // if ($event) {

    //     $qrCodeContent .= 'BEGIN:VEVENT' . PHP_EOL;
    //     $qrCodeContent .= 'SUMMARY:' . $reservation->getIdEvent()->getTitle() . PHP_EOL;
    //     $qrCodeContent .= 'DTSTART:' . date_format($reservation->getIdEvent()->getDateBeg(), 'Ymd\THis\Z') . PHP_EOL;
    //     $qrCodeContent .= 'DTEND:' . date_format($reservation->getIdEvent()->getDateEnd(), 'Ymd\THis\Z') . PHP_EOL;
    //     $qrCodeContent .= 'Nombre de places réservées: ' . $reservation->getNbPlace() . "\n";
    //     $qrCodeContent .= 'DESCRIPTION:' . $reservation->getIdEvent()->getDescription() . PHP_EOL;
    //     $qrCodeContent .= 'END:VEVENT';
    // }
      

    //     return $qrCodeContent;
    //  }



    public function qrcode($eventName, $eventId, $eventdesc, $eventdate1, $eventdate2)
    {
        // $url = 'https://www.google.com/search?q=';

        $objDateTime = new \DateTime('NOW');
        $dateString = $objDateTime->format('d-m-Y H:i:s');

        $path = dirname(__DIR__, 2).'/public/images';
        $data = "Event ID: $eventId\nEvent Name: $eventName\nEvent description: $eventdesc\nEvent date begin: ".$eventdate1->format('Y-m-d')."\nEvent date end: ".$eventdate2->format('Y-m-d');

        // set qrcode
        $result = $this->builder
            ->data($data)
            ->encoding(new Encoding('UTF-8'))
            ->errorCorrectionLevel(new ErrorCorrectionLevelHigh())
            ->size(400)
            ->margin(10)
            ->labelText($dateString)
            ->labelAlignment(new LabelAlignmentCenter())
            ->labelMargin(new Margin(15, 5, 5, 5))
            ->logoPath($path.'/img/logo.png')
            ->logoResizeToWidth('100')
            ->logoResizeToHeight('100')
            ->backgroundColor(new Color(221, 158, 3))
            ->build()
        ;
        
        //generate name
        $namePng = uniqid('', '') . '.png';

        //Save img png
        $result->saveToFile($path.'/qr-code/'.$namePng);

        return $result->getDataUri();
    
}
    }

    // /**
    //  * Fonction pour envoyer un email de confirmation avec un code QR pour une réservation
    //  */
    // public function sendConfirmationEmail(Reservation $reservation, $recipientEmail, $senderEmail, $senderName, $session) {
    //     // Configuration de l'e-mail
    //     $subject = 'Confirmation de réservation';
    //     $body = 'Bonjour ' . $reservation->getIdParticipant()->getEmail() . ',<br><br>Votre réservation pour l\'événement "'
    //         . $reservation->getIdEvent()->getTitle() . '" a été confirmée.<br><br>Voici votre code QR pour accéder à l\'événement:<br><br>';

    //     // Générer le code QR pour la réservation
    //     $qrCodeImage = $this->generateQRCodeImage($reservation);

    //     // Envoyer l'e-mail avec le code QR en pièce jointe
    //     $this->sendEmailWithQRCode($recipientEmail, $senderEmail, $senderName, $subject, $body, $qrCodeImage, $session);
    // }
// }