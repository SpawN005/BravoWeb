<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ReservationRepository;
use App\Form\ReservationType;
use App\Entity\Reservation;
use App\Entity\Event;
use Symfony\Component\HttpFoundation\Request;
use App\Form\SearchEventFormType;
use App\Repository\EventRepository;
use App\Repository\EventCategorieRepository;
use App\Form\EventType;
use App\Entity\EventCategorie;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry as PersistenceManagerRegistry;
use Swift_Mailer;
use Swift_Message;
use Swift_SmtpTransport;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use App\Repository\UserRepository;

use Symfony\Component\Validator\Validator\ValidatorInterface;

use Doctrine\Persistence\ObjectRepository;
use App\Service\SendMailService;
use App\Service\QrcodeService;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;


class EventMobileController extends AbstractController
{
    #[Route('/event/mobile', name: 'app_event_mobile')]
    public function index(): Response
    {
        return $this->render('event_mobile/index.html.twig', [
            'controller_name' => 'EventMobileController',
        ]);}



        #[Route('/eventJson', name: 'app_event_Json')]
    public function indexJson(EventRepository $er, NormalizerInterface $normalizer): Response
    {
        

        $events=$er->findAll();

         
        $eventNormalises = $normalizer->normalize($events,'json', ['groups' => "events"]);
        $json= json_encode($eventNormalises);
        return  new Response ($json);
    }


   





       
        
        


        
  
}
