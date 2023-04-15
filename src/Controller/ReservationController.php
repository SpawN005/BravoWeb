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
    public function reservationUser( ManagerRegistry $doctrine)
    {
        $reservations = $doctrine->getRepository(Reservation::class)->findAll();
        $eventRepository = $doctrine->getRepository(Event::class);
        $eventId = $eventRepository->findOneBy([])->getId(); // get the ID of the first event in the database
        $event = $eventRepository->find($eventId);
        return $this->render('reservation/indexUser.html.twig', [
            'reservations' => $reservations,
            'event'=>$event
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
public function deleteReservationUser($id, ReservationRepository $re, ManagerRegistry $doctrine, EventRepository $eventRepository): Response
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
    return $this->redirectToRoute("app_reservationUser");
}


    #[Route('/addReservation', name: 'app_addReservation')]
    public function addReservation(ManagerRegistry $doctrine, Request $request, EventRepository $eventRepository,SessionInterface $session): Response
{
    $reservations = new Reservation();
    $form = $this->createForm(ReservationType::class,$reservations);
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
         // Récupérer l'événement associé à la réservation
         $eventId = $reservations->getIdEvent()->getId();
         $event = $eventRepository->find($eventId);
       // Mettre à jour la capacité de l'événement
        $reservedSeats = $reservations->getNbPlace();
        $availableSeats = $event->getNbPlaceMax();
        if ($availableSeats === 0) {
            $message = 'L\'événement est complet !';
            $flashBag = $session->getFlashBag();
            $flashBag->add('error', $message);
            return $this->redirectToRoute('app_addReservation');
        }elseif ($reservedSeats > $availableSeats) {
            $message = 'Nombre de places indisponible !';
            $flashBag = $session->getFlashBag();
            $flashBag->add('error', $message);
            return $this->redirectToRoute('app_addReservation');
        }
        $this->updateEventCapacity($event, $reservedSeats);
        $em = $doctrine->getManager();
        $em->persist($reservations);
        $em->flush();
        return $this->redirectToRoute("app_reservationUser");
    }

    return $this->renderForm("reservation/createReservation.html.twig", [
        "form" => $form
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
        $event->setNbPlaceMax($newCapacity);

        $em = $this->getDoctrine()->getManager();
        $em->persist($event);
        $em->flush();
    }
}
