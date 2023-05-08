<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Serializer\Normalizer\NormalizableInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Doctrine\Persistence\ManagerRegistry;
use App\Form\EditProfileType;
use App\Form\UserType;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Messenger\Transport\Serialization\Serializer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Twilio\Serialize;
use Symfony\Component\Serializer\SerializerInterface;


class MobileController extends AbstractController
{
    #[Route('/mobile', name: 'app_mobile')]
    public function index(): Response
    {
        return $this->render('mobile/index.html.twig', [
            'controller_name' => 'MobileController',
        ]);
    }

    #[Route('/addusermobile', name: 'adduser', methods: ['GET','POST'])]
public function adduser(Request $request, NormalizerInterface $normalizer, UserPasswordEncoderInterface $userPasswordEncoder)
{

    

    $firstname= $request->query->get("firstName");
    $lastname= $request->query->get("lastName");
    $phone= $request->query->get("phone");
    $phone = $request->query->get("phone");
    if (strlen($phone) !== 8) {
        return new Response("phone number should be 8 digits");
    }


    $email= $request->query->get("email");
    $password= $request->query->get("password");
    $roles= $request->query->get("roles");


    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return new Response("email invalid");
    }

    $userRepository = $this->getDoctrine()->getRepository(User::class);
    $existingUser = $userRepository->findOneBy(['email' => $email]);

    if ($existingUser) {
        return new Response("email already exists");
    }

    $user = new User();
    $user->setPassword($password ?? ''); // Set the password field to a non-null string

    $user->setFirstName($firstname);
    $user->setLastName($lastname);
    $user->setEmail($email);
    $user->setPhone($phone);
    $user->setPassword(sha1($user->getPassword()));

    $user->setRoles(array($roles));
    $user->setBanned(0);
    $user->setIsVerified(0);


    try {
        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();
        $responseData = [
            'success' => true,
            'message' => 'User created successfully!',
            'user' => [
                'id' => $user->getId(),
                'firstName' => $user->getFirstName(),
                'lastName' => $user->getLastName(),
                'email' => $user->getEmail(),
                'password' => sha1($user->getPassword()), // pass the hashed password here
                'phone' => $user->getPhone(),
                'roles' => $user->getRoles(),
                'banned' => $user->isBanned(),
                'isVerified' => $user->isVerified(),

            ]
        ];

        $response = new JsonResponse($responseData);
        return $response;
    }catch(Exception $ex) {
        return new Response("exception " .$ex->getMessage());
    }
    

}

    
    
    
    

    #[Route('/loginmobile', name: 'LoginMobile', methods: ['GET','POST'])]
    public function login(Request $request, UserPasswordEncoderInterface $encoder, NormalizerInterface $normalizer)
{
    // Get the submitted email and password
    $email= $request->query->get("email");
    $password= $request->query->get("password");

    $userRepository = $this->getDoctrine()->getRepository(User::class);
    $user = $userRepository->findOneBy(['email' => $email]);
    if ($user) {
        $hashedPassword = sha1($password);

        if ( $hashedPassword == $user->getPassword()) {
            $email = $user->getEmail();
            $welcomeMessage = "Welcome $email!";
            $serializer = $this->get('serializer');
            $formatted = $serializer->normalize($user);
            $responseArray = [
                'welcomeMessage' => $welcomeMessage,
                'user' => $formatted
            ];
            return new JsonResponse($responseArray);
        }
        else {
            return new Response("password not found");
        }
    }
    else {
        return new Response("user not found");
    }


}

    

#[Route('/editprofilemobile', name: 'EditMobile', methods: ['GET','POST'])]
public function edit(Request $request, UserPasswordEncoderInterface $encoder, NormalizerInterface $normalizer)
{
    $id = $request->get("id");
    $em=$this->getDoctrine()->getManager();
    $user = $em->getRepository(User::class)->find($id);

    if (!$user) {
        return new Response('User not found.');
    }

    $firstname= $request->query->get("firstName");
    if ($firstname) {
        $user->setFirstName($firstname);
    }

    $lastname= $request->query->get("lastName");
    if ($lastname) {
        $user->setLastName($lastname);
    }

    $phone= $request->query->get("phone");
    if ($phone) {
        $user->setPhone($phone);
    }

    $email= $request->query->get("email");
    if ($email) {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return new Response("Invalid email address.");
        }
        $user->setEmail($email);
    }

    $password= $request->query->get("password");
    if ($password) {
        $user->setPassword($encoder->encodePassword($user, $password));
    }

    try {
        $em->persist($user);
        $em->flush();
        $responseData = [
            'success' => true,
            'message' => 'User updated successfully!',
            'user' => [
                'id' => $user->getId(),
                'firstName' => $user->getFirstName(),
                'lastName' => $user->getLastName(),
                'email' => $user->getEmail(),
                'phone' => $user->getPhone(),
                'roles' => $user->getRoles(),
                'banned' => $user->isBanned(),
                'isVerified' => $user->isVerified(),

            ]
        ];

        $response = new JsonResponse($responseData);
        return $response;
    } catch(Exception $ex) {
        return new Response("Exception: " .$ex->getMessage());
    }
}



}
