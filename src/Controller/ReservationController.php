<?php

namespace App\Controller;


use App\Repository\ReservationRepository;
use App\Form\ReservationType;
use App\Entity\Reservation;
use App\Entity\Event;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\EventRepository;
use Doctrine\Persistence\ObjectRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Service\SendMailService;
use App\Service\QrcodeService;
use App\Entity\User;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Swift_Mailer;
use Swift_Message;
use Swift_SmtpTransport;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;




class ReservationController extends AbstractController
{
    #[Route('/reservation', name: 'app_reservation')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $reservations = $entityManager
            ->getRepository(Reservation::class)
            ->findAll();

        return $this->render('reservation/index.html.twig', [
            'reservations' => $reservations,
        ]);
    }
    
   
    #[Route('/reservationUser', name:'app_reservationUser')]
    public function reservationUser(ManagerRegistry $doctrine)
    {
        $user = $doctrine->getRepository(User::class)->find(5);
        if (!$user) {
            throw $this->createNotFoundException('Utilisateur introuvable');
        }
        
        $reservations = $user->getReservations();
        
        return $this->render('reservation/indexUser.html.twig', [
            'reservations' => $reservations,
        ]);
    }
    
   

    

    


    #[Route('/deleteReservation/{id}', name: 'app_deleteReservation')]
public function deleteReservation($id, ReservationRepository $re, ManagerRegistry $doctrine, EventRepository $eventRepository): Response
{
    //récupérer la classe à supprimer
    $reservations = $re->find($id);
    if (!$reservations) {
        throw $this->createNotFoundException('Reservation not found for id '.$id);
    }
    //Action de suppression
    //récupérer l'Entitye manager
    $em = $doctrine->getManager();

    // Récupérer l'événement associé à la réservation
    $eventId = $reservations->getIdEvent()->getId();
    $event = $eventRepository->find($eventId);

    // Mettre à jour la capacité de l'événement
    $releasedSeats = $reservations->getNbPlace();
    $this->updateEventCapacity($event, -$releasedSeats);

    $em->remove($reservations);
    //La maj au niveau de la bd
    $em->flush();
    return $this->redirectToRoute("app_reservation");
}

    // #[Route('/deleteReservationUser/{id}', name: 'app_deleteReservationUser')]
    // public function deleteReservationUser($id, ReservationRepository $re, ManagerRegistry $doctrine): Response
    // {
    //     //récupérer la classe à supprimer
    //     $reservations = $re->find($id);
    //     if (!$reservations) {
    //         throw $this->createNotFoundException('Reservation not found for id '.$id);
    //     }
    //     //Action de suppression
    //     //récupérer l'Entitye manager
    //     $em = $doctrine->getManager();
    //     $em->remove($reservations);
    //     //La maj au niveau de la bd
    //     $em->flush();
    //     return $this->redirectToRoute("app_reservationUser");
    // }
    #[Route('/deleteReservationUser/{id}', name: 'app_deleteReservationUser')]
public function deleteReservationUser($id, ReservationRepository $re, ManagerRegistry $doctrine, EventRepository $eventRepository,
SessionInterface $session): Response
{
    //récupérer la classe à supprimer
    $reservations = $re->find($id);
    if (!$reservations) {
        throw $this->createNotFoundException('Reservation not found for id '.$id);
    }

    // vérifier si la date de début de l'événement est dans plus de 24h
    $eventDate = $reservations->getIdEvent()->getDateBeg();
    $now = new \DateTime();
    $diff = $now->diff($eventDate);
    if ($diff->days < 1) {
        $session->getFlashBag()->add('error', 'You cannot delete your reservation less than 1 day before the event');
        return $this->redirectToRoute('app_reservationUser');
    }

    //Action de suppression
    //récupérer l'Entitye manager
    $em = $doctrine->getManager();

    // Récupérer l'événement associé à la réservation
    $eventId = $reservations->getIdEvent()->getId();
    $event = $eventRepository->find($eventId);

    // Mettre à jour la capacité de l'événement
    $releasedSeats = $reservations->getNbPlace();
    $this->updateEventCapacity($event, -$releasedSeats);

    $em->remove($reservations);
    //La maj au niveau de la bd
    $em->flush();
    return $this->redirectToRoute("app_reservationUser");
}


#[Route('/addReservation/{id}', name: 'app_addReservation')]
public function addReservation(
    ManagerRegistry $doctrine,
    Request $request,
    EventRepository $eventRepository,
    SessionInterface $session,
    SendMailService $emailService,
    $id,
    QrcodeService $qrcodeService,
    Swift_mailer $mailer
): Response {
    $reservation = new Reservation();
    $form = $this->createForm(ReservationType::class, $reservation);
    
   
    $em=$this->getDoctrine()->getManager();
    $id_user = 5;
    $user = $em->getRepository(User::class)->find($id_user);
    // in index function
    // $eventId = 39; // replace with the ID of the desired event

    $eventRepository = $em->getRepository(Event::class);
    $event = $eventRepository->find($id);

    $qrcodeDataUri = $qrcodeService->qrcode($event->getTitle(), $event->getId(),$event->getDescription(),$event->getdateBeg(),$event->getdateEnd());

   

    $form->handleRequest($request);
    $userRepository = $doctrine->getRepository(User::class);



    if ($form->isSubmitted() && $form->isValid()) {
        // $eventId = $reservation->getIdEvent()->getId();
        $event = $eventRepository->find($id);
        $reservation = $form->getData();

        $reservedSeats = $reservation->getNbPlace();
        $availableSeats = $event->getNbPlaceMax();
        $reservation ->setIdEvent($event);

        if ($availableSeats === 0) {
            $this->addFlash('error', 'L\'événement est complet !');
            return $this->redirectToRoute('app_reservationUser');
        }

        if ($reservedSeats > $availableSeats) {
            $this->addFlash('error', 'Nombre de places indisponible !');
            return $this->redirectToRoute('app_createReservation');
        }

        $em = $doctrine->getManager();

        $this->updateEventCapacity($event, $reservedSeats);

        $em->persist($reservation);
        $em->flush();
        $sendmailer = $this->email($user,$mailer);
       
    
        
        $this->addFlash('success', 'La réservation a été effectuée avec succès !');

        return $this->redirectToRoute('app_reservationUser');
    }

    return $this->renderForm("reservation/createReservation.html.twig", [
        "form" => $form,
        // "qrCodeUrl" => $qrCodeUrl,
         "qrcodeDataUri" => $qrcodeDataUri,
    ]);
}

    #[Route('/updateReservation/{id}', name: 'app_updateReservation')]
    public function updateReservation($id, ReservationRepository $re, ManagerRegistry $doctrine, Request $request): Response
    {
        //récupérer la classe à modifier
        $reservations = $re->find($id);
        $form = $this->createForm(ReservationType::class,$reservations);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            //Action de MAJ
            $em =$doctrine->getManager();
            $em->flush();
            return $this->redirectToRoute("app_reservationUser");
        }
        return $this->renderForm("reservation/updateReservation.html.twig", [
            "form" => $form
        ]);
    }

    
    private function updateEventCapacity(Event $event, $reservedSeats)
    {
        $currentCapacity = $event->getNbPlaceMax();
        $newCapacity = $currentCapacity - $reservedSeats;
        
        // Vérifier si la nouvelle capacité est supérieure ou égale à zéro
        if ($newCapacity >= 0) {
            $event->setNbPlaceMax($newCapacity);
    
            $em = $this->getDoctrine()->getManager();
            $em->persist($event);
            $em->flush();
        }
    }



    public function email ($user,$mailer){
        // Create a new SMTP transport with the desired configuration
    $dsn = getenv('MAILER_DSN');
    $transport = new Swift_SmtpTransport('smtp.gmail.com', 587, 'tls');
    $transport->setUsername('meriam123.hammi@gmail.com');
    $transport->setPassword('wnevuhcvabtqhhiz');

    $mailer = new Swift_Mailer($transport);

    //BUNDLE MAILER
    $message = (new Swift_Message('Confirmation reservation'))
        ->setFrom('meriam123.hammi@gmail.com')
        ->setTo($user->getEmail())
        ->setBody(" Bonjour,\n \nNous vous confirmons votre réservation pour l'événement");

    //send mail
    $mailer->send($message);
    }


}
