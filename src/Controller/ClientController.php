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
use libphonenumber\PhoneNumberUtil;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\NumberParseException;
use Twilio\Rest\Client;



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

    

#[Route('/client/profile/modifier', name: 'clientProfile',methods: ['GET', 'POST'])]

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

/**
 * @Route("/sendVerificationCode", methods={"POST"})
 */
public function sendVerificationCode(Request $request)
{
    // Extract the phone number from the form submission
    $phoneNumber = $request->request->get('phone');

    // Check whether the phone number already contains the country code
    $phoneUtil = PhoneNumberUtil::getInstance();
    try {
        $numberProto = $phoneUtil->parse($phoneNumber, 'TN');
        if (!$phoneUtil->isValidNumber($numberProto)) {
            throw new NumberParseException(
                NumberParseException::NOT_A_NUMBER,
                'Invalid phone number'
            );
        }
        if ($phoneUtil->getRegionCodeForNumber($numberProto) == 'TN') {
            $phoneNumber = $phoneUtil->format($numberProto, PhoneNumberFormat::E164);
        } else {
            throw new NumberParseException(
                NumberParseException::INVALID_COUNTRY_CODE,
                'Invalid country code'
            );
        }
    } catch (NumberParseException $e) {
        // Handle the exception if the phone number is not valid
        return new JsonResponse(['success' => false, 'message' => $e->getMessage()]);
    }

    // Generate a random verification code
    $verificationCode = rand(1000, 9999);

    // Store the verification code in the user's session
    $session = $request->getSession();
    $session->set('verification_code', $verificationCode);

    // Send the verification code via Twilio
    $sid = 'AC722e32116c6083cff1c7e8898c7a1dd5';
    $token = '7a9334e17663891b9f651c3fdcbef544';
    $client = new Client($sid, $token);
    dump($phoneNumber);

    $message = $client->messages->create(
        $phoneNumber, // the phone number to send the verification code to
        array(
            'from' => '+15076088911', // your Twilio phone number
            'body' => 'Your verification code is: ' . $verificationCode
        )
    );

    // Return a JSON response indicating success
    return new JsonResponse(['success' => true]);
}



  



}






