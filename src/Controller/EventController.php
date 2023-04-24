<?php

namespace App\Controller;
use App\Form\SearchEventFormType;
use App\Repository\EventRepository;
use App\Form\EventType;
use App\Entity\Event;
use App\Entity\EventCategorie;
use App\Repository\ReservationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry as PersistenceManagerRegistry;
use Swift_Mailer;
use Swift_Message;
use Swift_SmtpTransport;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;


class EventController extends AbstractController
{   
    #[Route('/event', name: 'app_event')]
    public function index(Request $request, EventRepository $eventRepository): Response
    {
        $form = $this->createForm(SearchEventFormType::class);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $ev = $eventRepository->findSearch($data['nbPlaceMax'], $data['categorie']);
        } else {
            $ev = $eventRepository->findAll();
        }
    
        return $this->render('event/index.html.twig', [
            'ev' => $ev,
            'form' => $form->createView(),
        ]);
    }
    
    
    #[Route('/event/{id}', name: 'app_eventUser')]
    public function indexUser(int $id,Request $request, EventRepository $eventRepository,EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SearchEventFormType::class);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $ev = $eventRepository->findSearch($data['nbPlaceMax'], $data['categorie']);
        } else {
            $ev = $eventRepository->findAll();
        }
       

    
        return $this->render('event/indexUser.html.twig', [
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
    public function show($id, EventRepository $rep, Request $request): Response
    {
        //Utiliser find by id
        $event = $rep->find($id);
        return $this->render('event/detailsEvent.html.twig', [
            'event' => $event,
        ]);
    }

    #[Route('/detailEventUser/{id}', name: 'app_detailEventUser', methods: ["GET", "POST"] )]
    public function showUser($id, EventRepository $rep, Request $request): Response
    {
        //Utiliser find by id
        $event = $rep->find($id);
        $eventId = $event->getId();
        return $this->render('event/detailsEventUser.html.twig', [
            'event' => $event,
            'eventId' => $eventId
        ]);
    }




    #[Route('/deleteEvent/{id}', name: 'app_deleteEvent')]
    public function deleteEvent($id, EventRepository $rep, ReservationRepository $reservationRep, ManagerRegistry $doctrine, SessionInterface $session,Swift_Mailer $mailer): Response
    {
        //récupérer la classe à supprimer
        $event=$rep->find($id);
        if (!$event) {
            throw $this->createNotFoundException('Event not found for id '.$id);
        }
        // Récupérer toutes les réservations de cet événement
        $reservations = $reservationRep->findBy(['id_event' => $event]);
    
        // Supprimer l'événement
        $em=$doctrine->getManager();
        $em->remove($event);
    
       
       // Envoyer une notification à chaque utilisateur ayant réservé des places dans cet événement
foreach ($reservations as $reservation) {
    $user = $reservation->getIdParticipant();
    $eventTitle = $event->getTitle();
    $message = 'The event "' . $eventTitle . '" has been cancelled.';
}
    $session->getFlashBag()->add('danger', $message);
    $this->emailAnnulation($user, $eventTitle, $mailer);

        // Enregistrer les modifications dans la base de données
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
                   if($form->isSubmitted()&& $form->isValid()){
                    $file = $form['image']->getData();
            $fileName = $file->getClientOriginalName();
            $file->move("C:/xampp/htdocs/img", $fileName);
            $event->setImage($fileName);
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
                   public function updateEvent($id, EventRepository $eventRepository, ManagerRegistry $doctrine, Request $request,
                   EntityManagerInterface $entityManager,Swift_Mailer $mailer, ReservationRepository $reservationRep)
                   {
                       //récupérer la classe à modifier
                       $event = $eventRepository->find($id);
                       $form = $this->createForm(EventType::class,$event);
                       $form->handleRequest($request);
                       if ($form->isSubmitted() && $form->isValid()) {
                        $file = $form['image']->getData();
                        $fileName = $file->getClientOriginalName();
                        $file->move("C:/xampp/htdocs/img", $fileName);
                        $event->setImage($fileName);
                        $entityManager->flush();

 // Récupérer toutes les réservations de cet événement
        $reservations = $reservationRep->findBy(['id_event' => $event]);
    
                         // Envoyer une notification à chaque utilisateur ayant réservé des places dans cet événement
                        foreach ($reservations as $reservation) {
                              $user = $reservation->getIdParticipant();
                             $eventTitle = $event->getTitle();
                     }
                            
                            $this->emailUpdate($user, $eventTitle, $mailer);
                           return $this->redirectToRoute("app_event");
                       }
                       return $this->renderForm("event/updateEvent.html.twig", array("form"=>$form));
                   }   
                   
                   public function emailAnnulation ($user,$eventTitle, $mailer){
                    // Create a new SMTP transport with the desired configuration
                    $dsn = getenv('MAILER_DSN');
                    $transport = new Swift_SmtpTransport('smtp.gmail.com', 587, 'tls');
                    $transport->setUsername('meriam123.hammi@gmail.com');
                    $transport->setPassword('wnevuhcvabtqhhiz');
                
                    $mailer = new Swift_Mailer($transport);
                
                    //BUNDLE MAILER
                    $message = (new Swift_Message('Annulaion evenement'))
                        ->setFrom('meriam123.hammi@gmail.com')
                        ->setTo($user->getEmail())
                        ->setBody(" Bonjour,\n Nous vous informons que l'événement \"$eventTitle\"pour lequel vous avez réservé des places a été annulé.");
                
                    //send mail
                    $mailer->send($message);
                }
                public function emailUpdate ($user,$eventTitle, $mailer){
                    // Create a new SMTP transport with the desired configuration
                    $dsn = getenv('MAILER_DSN');
                    $transport = new Swift_SmtpTransport('smtp.gmail.com', 587, 'tls');
                    $transport->setUsername('meriam123.hammi@gmail.com');
                    $transport->setPassword('wnevuhcvabtqhhiz');
                
                    $mailer = new Swift_Mailer($transport);
                
                    //BUNDLE MAILER
                    $message = (new Swift_Message('Modification evenement'))
                        ->setFrom('meriam123.hammi@gmail.com')
                        ->setTo($user->getEmail())
                        ->setBody(" Bonjour,\n Nous vous informons que l'événement \"$eventTitle\"pour lequel vous avez réservé des places a été modifié, veuillez consulter dans notre site web.");
                
                    //send mail
                    $mailer->send($message);
                }
}
