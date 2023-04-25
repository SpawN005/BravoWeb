<?php

namespace App\Controller;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\UserRepository;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use App\Form\EditProfileType;
use App\Form\UserEditType;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\JsonResponse;
use Twilio\Rest\Client;
use Symfony\Component\HttpFoundation\Session\SessionInterface;






class ClientController extends AbstractController
{
    private $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;

        
    }

    
    #[Route('/front', name: 'front',methods: ['GET', 'POST'])]
    public function Front(): Response
    {
        return $this->render('frontTest.html.twig');
    }



    #[Route('/client', name: 'client',methods: ['GET', 'POST'])]
    public function Client(Security $security): Response
    {

        if ($security->getUser()) {
            if (in_array("ROLE_USER", $security->getUser()->getRoles())) {
                return $this->render('frontUser.html.twig', ['controller_name' => 'ClientController']);
            } else {
                return $this->redirectToRoute("app_login");

            }
        } else {
            return $this->redirectToRoute("app_login");
        }
    }

    

    #[Route('/client/profile/modifier', name: 'clientProfile', methods: ['GET', 'POST'])]

public function userProfile(ManagerRegistry $doctrine, Request $request, UserRepository $repository, SluggerInterface $slugger): response
{
    $user= $this->getUser();
    $form=$this->createForm(UserEditType::class,$user);

    $form->handleRequest($request);
    $errors = $form->getErrors();


    if ($form->isSubmitted() && $form->isValid()) {

        $photo = $form->get('image')->getData();


        // this condition is needed because the 'brochure' field is not required
        // so the PDF file must be processed only when a file is uploaded
        if ($photo) {
            $originalFilename = pathinfo($photo->getClientOriginalName(), PATHINFO_FILENAME);
            // this is needed to safely include the file name as part of the URL
            $safeFilename = $slugger->slug($originalFilename);
            $newFilename = $safeFilename . '-' . uniqid() . '.' . $photo->guessExtension();

            // Move the file to the directory where brochures are stored
            try {
                $photo->move(
                    $this->getParameter('app.path.product_images'),
                    $newFilename
                );
            } catch (FileException $e) {
                // ... handle exception if something happens during file upload
            }

            // updates the 'brochureFilename' property to store the PDF file name
            // instead of its contents
            $user->setImage($newFilename);
        }

              

        $em = $doctrine->getManager();
        $em->persist($user);
        $em->flush();

        return $this->redirectToRoute('clientProfile');
    }

    return $this->render('client/index.html.twig', [
        'form' => $form->createView(),
        'errors' => $errors

    ]);
}



 #[Route('/client/profile/modifier/{id}', name: 'deleteProfile')]
     
public function DeleteUser(EntityManagerInterface $entityManager,User $user, UserRepository $repository,$id,ManagerRegistry $doctrine,Request $request ){

    $session = $request->getSession();

        $user = $repository->find($id);
        if (!$user || !$user->getId()) {
            throw new \Exception("User not found or has no identifier");
        }
        $entityManager =$doctrine->getManager();
        $entityManager->remove($user);
        $entityManager->flush();

        $session->remove('user_id');
        return $this->redirectToRoute('registration');

  }

  #[Route('/client/profile/modifier/{id}/sendVerificationCode', methods: ['POST'])]
  public function sendVerificationCode(Request $request)
  {

    $phoneNumber = '+21655249321';

      // Check whether the phone number already contains the country code
      if (!preg_match('/^\+216\d{8}$/', $phoneNumber)) {
          return new JsonResponse(['success' => false, 'message' => 'Invalid phone number']);
      }
  
      // Generate a random verification code
      $verificationCode = rand(1000, 9999);
  
      // Store the verification code in the user's session
      $session = $request->getSession();
      $session->set('verification_code', $verificationCode);
  
      $sid = 'AC722e32116c6083cff1c7e8898c7a1dd5';
      $token = '7a9334e17663891b9f651c3fdcbef544';
      $client = new Client($sid, $token);
  
      $message = $client->messages->create(
          $phoneNumber, // the phone number to send the verification code to
          array(
              'from' => '+15076088911',
              'body' => 'Your TunART verification code is: ' . $verificationCode
          )
      );
  
      return new JsonResponse(['success' => true]);
  }

#[Route('/client/profile/modifier/{id}/verifyCode', methods: ['POST'])]
public function verifyCode(Request $request, EntityManagerInterface $em): JsonResponse
{
    // Get the verification code entered by the user
    $code = $request->request->get('code');
    
    // Get the verification code stored in the user's session
    $session = $request->getSession();
    $verificationCode = $session->get('verification_code');
    
    // Compare the two codes to check if they match
    if ($code == $verificationCode) {
        $userId = $this->getUser()->getId();
        $user = $em->getRepository(User::class)->find($userId);
        $user->setIsVerified(1);
        $em->flush();
        $session->set('is_verified', 1);
        
        // Return a success response
        return new JsonResponse(['success' => true]);
    } else {
        // If the codes don't match, return an error response
        return new JsonResponse(['success' => false, 'message' => 'Invalid verification code']);
    }
}

  


  



}






