<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Service\MailerService;
use App\Service\ValidService;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Doctrine\Persistence\ManagerRegistry;


class RegistrationaaaController extends AbstractController
{
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }


    #[Route('/registration', name: 'registration', methods: ['GET', 'POST'])]

    public function index(Request $request, ManagerRegistry $doctrine)
    {
        $user = new User();

        $form = $this->createForm(UserType::class, $user);
        // $form->add('Add',SubmitType::class);

        $form->handleRequest($request);
        $errors = $form->getErrors();
        $user->setBanned(0);
        if ($form->isSubmitted() && $form->isValid()) {
            // Encode the new users password
            $user->setPassword($this->passwordHasher->hashPassword($user, $user->getPassword()));

            // Set their role


            // Save
            $em = $doctrine->getManager();
            $em->persist($user);
            $em->flush();
            $this->addFlash('message', 'Profil created');



            // $mailMessage=$user->getuserName();
            // $mailer->sendEmail($mailMessage);
            return $this->redirectToRoute('app_login');
        }

        return $this->render('registration/index.html.twig', [
            'form' => $form->createView(),
            'errors' => $errors
        ]);
    }
}
