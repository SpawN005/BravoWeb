<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ReservationRepository;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Repository\UserRepository;
use App\Repository\EventRepository;
use App\Entity\Reservation;




class ReservationMobileController extends AbstractController
{
    #[Route('/reservation/mobile', name: 'app_reservation_mobile')]
    public function index(): Response
    {
        return $this->render('reservation_mobile/index.html.twig', [
            'controller_name' => 'ReservationMobileController',
        ]);
    }

    #[Route('/reservationJson', name: 'app_reservation_json')]
    public function indexJson(ReservationRepository $rRepo, NormalizerInterface $normalizer): Response
    {
        $reservation=$rRepo->findAll();

         
        $reservationNormalises = $normalizer->normalize($reservation,'json', ['groups' => "reservations"]);
        $json= json_encode($reservationNormalises);
        return  new Response ($json);
    }

    #[Route('/addReservationJson', name: 'app_addR')]
public function addReservation(Request $request, ValidatorInterface $validator,UserRepository $rep, EventRepository $er
,NormalizerInterface $Normalizer): Response
{

    $user = $this->getUser();
     // Récupérer les paramètres depuis la requête
     $nb_place = $request->get('nb_place');
   

     
     // Créer une nouvelle réclamation avec les paramètres
     $reservation = new Reservation();
     $reservation->setNbPlace($nb_place);
     $reservation->setIsConfirmed(1);
    
    $reservation->setIdEvent($er->findOneBy([]));
    $reservation->setIdParticipant($rep->findOneBy([]));
    
                $em = $this->getDoctrine()->getManager();
                $em->persist($reservation);
                $em->flush();

    $jsonContent = $Normalizer->normalize($reservation, 'json',['groups'=>"reservations"]);
    return new Response("La réservation a bien été ajoutee". json_encode($jsonContent) );
}

#[Route('/updateReservationJson/{id}', name: 'app_updateR')]
public function updateReservationJson(Request $request, ReservationRepository $rr, NormalizerInterface $normalizer, ValidatorInterface $validator, int $id): Response
{
    // Récupérer la réservation à modifier
    $reservation = $rr->find($id);

    if (!$reservation) {
        throw $this->createNotFoundException('La réservation avec l\'id '.$id.' n\'existe pas.');
    }

    // Récupérer les paramètres depuis la requête
    $nb_place = $request->get('nb_place');

    // Mettre à jour la réservation avec les nouveaux paramètres
    $reservation->setNbPlace($nb_place);
    // Enregistrer la réservation mise à jour
    $em = $this->getDoctrine()->getManager();
    $em->persist($reservation);
    $em->flush();

    // Renvoyer la réponse JSON avec la réservation mise à jour
    $reservationNormalises = $normalizer->normalize($reservation, 'json', ['groups' => 'reservations']);
    $json = json_encode($reservationNormalises);

    return new Response('La réservation a été mise à jour : '.$json);
}


#[Route('/deleteReservationJson/{id}', name: 'app_deleteR')]
public function deleteReservationJson($id, ReservationRepository $rr): Response
{
    $reservation = $rr->find($id);

    
    $em = $this->getDoctrine()->getManager();
    $em->remove($reservation);
    $em->flush();

    return new Response("La réservation a bien été supprimée");
}











}
