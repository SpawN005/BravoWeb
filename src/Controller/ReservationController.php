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
use App\Service\QRCodeService;
use App\Entity\User;
use Symfony\Component\HttpFoundation\RedirectResponse;



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
        $event = $user->getEvents(); // récupérer le premier événement associé à l'utilisateur
        
        return $this->render('reservation/indexUser.html.twig', [
            'reservations' => $reservations,
            'event' => $event
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


//     #[Route('/addReservation', name: 'app_addReservation')]
//     public function addReservation(ManagerRegistry $doctrine, Request $request, EventRepository $eventRepository, 
//     SessionInterface $session, EmailService $emailService, QRCodeService $qrCodeService): Response
//     {
//     $reservations = new Reservation();
//     $form = $this->createForm(ReservationType::class,$reservations);
//     $form->handleRequest($request);
//     if ($form->isSubmitted() && $form->isValid()) {
//          // Récupérer l'événement associé à la réservation
//          $eventId = $reservations->getIdEvent()->getId();
//          $event = $eventRepository->find($eventId);
//        // Mettre à jour la capacité de l'événement
//         $reservedSeats = $reservations->getNbPlace();
//         $availableSeats = $event->getNbPlaceMax();
//         if ($availableSeats === 0) {
//             $message = 'L\'événement est complet !';
//             $flashBag = $session->getFlashBag();
//             $flashBag->add('error', $message);
//             return $this->redirectToRoute('app_addReservation');
//         }elseif ($reservedSeats > $availableSeats) {
//             $message = 'Nombre de places indisponible !';
//             $flashBag = $session->getFlashBag();
//             $flashBag->add('error', $message);
//             return $this->redirectToRoute('app_addReservation');
//         }
//         $this->updateEventCapacity($event, $reservedSeats);
//         $em = $doctrine->getManager();
//         $em->persist($reservations);
//         $em->flush();

//       // Générer le code QR
// $qrCode = $qrCodeService->generateQRCodeImage($reservations);
// $qrCodePath = 'images/' . $reservations->getId() . '.png';
// $qrCodeUrl = $this->getParameter('app.base_url') . '/' . $qrCodePath;
// $qrCode->save($qrCodePath);

// // Envoyer l'e-mail de confirmation
// $recipientEmail = $reservations->getIdParticipant()->getEmail();
// $subject = 'Confirmation de réservation';
// $body = $this->renderView('email/confirmation.html.twig', [
//     'reservation' => $reservations,
//     'qrCodeUrl' => $qrCodeUrl,
// ]);
// $emailService->sendEmail($recipientEmail, $subject, $body);

      
//       // Ajouter un message flash pour confirmer que la réservation a été effectuée avec succès
//       $message = 'La réservation a été effectuée avec succès !';
//       $flashBag = $session->getFlashBag();
//       $flashBag->add('success', $message);

//       // Rediriger l'utilisateur vers la page de détails de l'événement
//       return $this->redirectToRoute('event_details', ['id' => $event->getId()]);
//   }

//     return $this->renderForm("reservation/createReservation.html.twig", [
//         "form" => $form
//     ]);
// }


// #[Route('/addReservation', name: 'app_addReservation')]
// public function addReservation(
//     ManagerRegistry $doctrine,
//     Request $request,
//     EventRepository $eventRepository,
//     SessionInterface $session,
//     SendMailService $emailService,
//     // QRCodeService $qrCodeService
// ): Response {
//     $reservation = new Reservation();
//     $form = $this->createForm(ReservationType::class, $reservation);
//     $form->handleRequest($request);

//     if ($form->isSubmitted() && $form->isValid()) {
//         $eventId = $reservation->getIdEvent()->getId();
//         $event = $eventRepository->find($eventId);

//         $reservedSeats = $reservation->getNbPlace();
//         $availableSeats = $event->getNbPlaceMax();

//         if ($availableSeats === 0) {
//             $this->addFlash('error', 'L\'événement est complet !');
//             return $this->redirectToRoute('app_reservationUser');
//         }

//         if ($reservedSeats > $availableSeats) {
//             $this->addFlash('error', 'Nombre de places indisponible !');
//             return $this->redirectToRoute('app_createReservation');
//         }

//         $em = $doctrine->getManager();

//         $this->updateEventCapacity($event, $reservedSeats);

//         $em->persist($reservation);
//         $em->flush();

//         // $qrCode = $qrCodeService->generateQRCodeImage($reservation);
//         // $qrCodePath = 'images/' . $reservation->getId() . '.png';
//         // $qrCodeUrl = $this->getParameter('app.base_url') . '/' . $qrCodePath;
//         // $qrCode->save($qrCodePath);

//         // $recipientEmail = $reservation->getIdParticipant()->getEmail();
//         // $subject = 'Confirmation de réservation';
//         // $body = $this->renderView('email/confirmation.html.twig', [
//         //     'reservation' => $reservation,
//         //     // 'qrCodeUrl' => $qrCodeUrl,
//         // ]);
//         // $emailService->sendEmail($recipientEmail, $subject, $body);
        
//         // Envoi du mail
//         $emailService->sendMail(
//             'meriam123.hammi@gmail.com', 'Tun art',
//             $reservation->getIdParticipant()->getEmail(),

//             'Account Approval Confirmation',
//             'confirmation',
           
//         );

//         $this->addFlash('success', 'La réservation a été effectuée avec succès !');

//         return $this->redirectToRoute('app_addReservation');
//     }

//     return $this->renderForm("reservation/createReservation.html.twig", [
//         "form" => $form
//     ]);
// }

#[Route('/addReservation', name: 'app_addReservation')]
public function addReservation(
    ManagerRegistry $doctrine,
    Request $request,
    EventRepository $eventRepository,
    SessionInterface $session,
    SendMailService $emailService,

    // QRCodeService $qrCodeService
): Response {
    $reservation = new Reservation();
    $form = $this->createForm(ReservationType::class, $reservation);
    $form->handleRequest($request);
    $userRepository = $doctrine->getRepository(User::class);


    if ($form->isSubmitted() && $form->isValid()) {
        $eventId = $reservation->getIdEvent()->getId();
        $event = $eventRepository->find($eventId);

        $reservedSeats = $reservation->getNbPlace();
        $availableSeats = $event->getNbPlaceMax();

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

        // $qrCode = $qrCodeService->generateQRCodeImage($reservation);
        // $qrCodePath = 'images/' . $reservation->getId() . '.png';
        // $qrCodeUrl = $this->getParameter('app.base_url') . '/' . $qrCodePath;
        // $qrCode->saveToFile($qrCodePath);

        if ($reservation->getIdParticipant() === null) {
            $this->addFlash('error', 'La réservation doit être liée à un utilisateur !');
            return $this->redirectToRoute('app_reservationUser');
        }
            // Récupérer l'utilisateur d'id 5
            $user = $userRepository->find(5);
            $recipientEmail = $user->getEmail();

        $subject = 'Confirmation de réservation';
        // $body = $this->renderView('email/confirmation.html.twig', [
        //     // 'qrCodeUrl' => $qrCodeUrl,
        //     'r' => $reservation,
        // ]);
        $body = "Votre réservation pour l'événement #" . $eventId . " a été effectuée avec succès.";

        $emailService->sendMail(
            'meriam123.hammi@gmail.com',
            'Tun art',
            'myriam123.hammi@gmail.com',
            $subject,
            'confirmation',
            ['body' => $body]
        );
        
        
        
        $this->addFlash('success', 'La réservation a été effectuée avec succès !');

        return $this->redirectToRoute('app_reservationUser');
    }

    return $this->renderForm("reservation/createReservation.html.twig", [
        "form" => $form,
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

    // private function updateEventCapacity(Event $event, $reservedSeats)
    // {
    //     $currentCapacity = $event->getNbPlaceMax();
    //     $newCapacity = $currentCapacity - $reservedSeats;
    //     $event->setNbPlaceMax($newCapacity);

    //     $em = $this->getDoctrine()->getManager();
    //     $em->persist($event);
    //     $em->flush();
    // }
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
    


}
