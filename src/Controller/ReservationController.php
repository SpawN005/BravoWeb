<?php

namespace App\Controller;


use App\Repository\ReservationRepository;
use App\Form\ReservationType;
use App\Entity\Reservation;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;


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
    
        return $this->render('reservation/indexUser.html.twig', [
            'reservations' => $reservations,
        ]);
    }
    

    

    #[Route('/deleteReservation/{id}', name: 'app_deleteReservation')]
    public function deleteReservation($id, ReservationRepository $re, ManagerRegistry $doctrine): Response
    {
        //récupérer la classe à supprimer
        $reservations = $re->find($id);
        if (!$reservations) {
            throw $this->createNotFoundException('Reservation not found for id '.$id);
        }
        //Action de suppression
        //récupérer l'Entitye manager
        $em = $doctrine->getManager();
        $em->remove($reservations);
        //La maj au niveau de la bd
        $em->flush();
        return $this->redirectToRoute("app_reservation");
    }
    #[Route('/deleteReservationUser/{id}', name: 'app_deleteReservationUser')]
    public function deleteReservationUser($id, ReservationRepository $re, ManagerRegistry $doctrine): Response
    {
        //récupérer la classe à supprimer
        $reservations = $re->find($id);
        if (!$reservations) {
            throw $this->createNotFoundException('Reservation not found for id '.$id);
        }
        //Action de suppression
        //récupérer l'Entitye manager
        $em = $doctrine->getManager();
        $em->remove($reservations);
        //La maj au niveau de la bd
        $em->flush();
        return $this->redirectToRoute("app_reservationUser");
    }
    

    #[Route('/addReservation', name: 'app_addReservation')]
    public function addReservation(ManagerRegistry $doctrine, Request $request): Response
{
    $reservations = new Reservation();
    $form = $this->createForm(ReservationType::class,$reservations);
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
        //Action d'ajout
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
}
