<?php

namespace App\Controller;
use App\Form\SearchEventFormType;
use App\Repository\EventRepository;
use App\Form\EventType;
use App\Entity\Event;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ManagerRegistry as PersistenceManagerRegistry;


class EventController extends AbstractController
{ 
    #[Route('/event', name: 'app_event')]
    public function index(Request $request, EventRepository $eventRepository): Response
    {
        $form = $this->createForm(SearchEventFormType::class);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $ev = $eventRepository->findSearch($data->getDateBeg(), $data->getDateEnd(), $data->getNbPlaceMax(), $data->getCategorie());
        } else {
            $ev = $eventRepository->findAll();
        }
    
        return $this->render('event/index.html.twig', [
            'ev' => $ev,
            'form' => $form->createView(),
        ]);
    }
    
    

    
    

    


    // #[Route('/readEvent', name: 'app_readEvent')]
    // public function readEvent(EventRepository $eventRepository): Response
    // {
    //     //Utiliser findAll()
    //     $events = $eventRepository->findAll();
    //     return $this->render('event/AffichageEvent.html.twig', [
    //         'event' => $events,
    //     ]);
    // }
    
    // #[Route('/detailEvent', name: 'app_detailEvent')]
    // public function detailEvent(EventRepository $eventRepository): Response
    // {
        
    //     //Utiliser findAll()
    //     $events = $eventRepository->findAll();
    //     return $this->render('event/detailsEvent.html.twig', [
    //         'event' => $events,
    //     ]);
    // }

    #[Route('/detailEvent/{id}', name: 'app_detailEvent', methods: ["GET", "POST"] )]
    public function show($id, EventRepository $rep, Request $request, PersistenceManagerRegistry $doctrine): Response
    {
        //Utiliser find by id
        $event = $rep->find($id);
        return $this->render('event/detailsEvent.html.twig', [
            'event' => $event,
        ]);
    }




    #[Route('/deleteEvent/{id}', name: 'app_deleteEvent')]
    public function delecteEvent($id, EventRepository $rep, 
    ManagerRegistry $doctrine): Response
    {
        //récupérer la classe à supprimer
        $event=$rep->find($id);
        if (!$event) {
            throw $this->createNotFoundException('Event not found for id '.$id);
        }
        //Action de suppression
        //récupérer l'Entitye manager
        $em=$doctrine->getManager();
        $em->remove($event);
        //La maj au niveau de la bd
        $em->flush();
        return $this->redirectToRoute('app_event');
    }

    #[Route('/addEvent', name: 'app_addEvent')]
    public function addEvent(ManagerRegistry $doctrine,
    Request $request)
{
    $event= new Event();
$form=$this->createForm(EventType::class,$event);
                   $form->handleRequest($request);
                   if($form->isSubmitted()){
                    //Action d'ajout
                       $em =$doctrine->getManager() ;
                       $em->persist($event);
                       $em->flush();
            return $this->redirectToRoute("app_event");
        }
    return $this->renderForm("event/createEvent.html.twig",
                       array("form"=>$form));
                   }

                   #[Route('/updateEvent/{id}', name: 'app_updateEvent')]
                   public function updateEvent($id, EventRepository $eventRepository, ManagerRegistry $doctrine, Request $request)
                   {
                       //récupérer la classe à modifier
                       $event = $eventRepository->find($id);
                       $form = $this->createForm(EventType::class,$event);
                       $form->handleRequest($request);
                       if($form->isSubmitted()){
                           //Action de MAJ
                           $em =$doctrine->getManager() ;
                           $em->flush(); // modification de cette ligne
                           return $this->redirectToRoute("app_event");
                       }
                       return $this->renderForm("event/createEvent.html.twig", array("form"=>$form));
                   }          
}
