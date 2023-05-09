<?php

namespace App\Controller;

use App\Entity\Artwork;
use App\Entity\Blog;
use App\Entity\Commentsoeuvre;
use App\Entity\Categorie;
use App\Entity\Event;
use App\Entity\Noteoeuvre;
use App\Entity\Reclamation;
use App\Entity\Reservation;
use App\Entity\User;
use App\Entity\Donation;
use App\Form\ArtworkType;
use App\Form\CommentoeuvreType;
use App\Form\NoteOeuvreType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CommentsoeuvreRepository;
use App\Repository\EventRepository;
use App\Repository\NoteoeuvreRepository;
use App\Repository\ReclamationRepository;
use App\Repository\ReservationRepository;
use App\Repository\TypeReclamationRepository;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Exception;
use Symfony\Component\Serializer\Normalizer\NormalizableInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Form\Test\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/mobile')]
class MobileController extends AbstractController
{
    #[Route('/artwork')]
    public function index(EntityManagerInterface $entityManager, NormalizerInterface $normalizer): Response

    {
        $artworks = $entityManager
            ->getRepository(Artwork::class)
            ->findAll();

        $jsonContent = $normalizer->normalize($artworks, 'json');

        return new Response(json_encode($jsonContent));
    }
    #[Route('/artwork/categorie')]
    public function ShowArtCat(EntityManagerInterface $entityManager, NormalizerInterface $normalizer): Response

    {
        $cat = $entityManager
            ->getRepository(Categorie::class)
            ->findAll();

        $jsonContent = $normalizer->normalize($cat, 'json');

        return new Response(json_encode($jsonContent));
    }
    #[Route('/artwork/new')]
    public function newArt(EntityManagerInterface $entityManager, Request $request, NormalizerInterface $normalizer): Response

    {

        $filesystem = new Filesystem();
        $filesystem->copy($request->get('url'), "C:/xampp/htdocs/img/" . $request->get("title") . ".png");


        $artwork = new Artwork();
        $artwork->setTitle($request->get("title"));
        $artwork->setDescription($request->get("description"));
        $artwork->setUrl($request->get("title") . ".png");
        $categorieName = $request->get("categorie");
        $id = $request->get("user");

        $user = $entityManager->getRepository(User::class)->find($id);
        $categorie = $entityManager->getRepository(Categorie::class)->findOneBynomcategorie($categorieName);
        $artwork->setOwner($user);
        $artwork->setCategorie($categorie);

        $entityManager->persist($artwork);
        $entityManager->flush();
        $jsonContent = $normalizer->normalize($artwork, 'json');

        return new Response("Art added Successfully " . json_encode($jsonContent));
    }
    #[Route('/artwork/update/{id}')]
    public function updateArt(EntityManagerInterface $entityManager, $id, Request $request, NormalizerInterface $normalizer): Response

    {
        $artwork =  $entityManager->getRepository(Artwork::class)->find($id);
        $categorieName = $request->get("categorie");
        $categorie = $entityManager->getRepository(Categorie::class)->findOneBynomcategorie($categorieName);
        $artwork->setCategorie($categorie);
        $artwork->setUrl($request->get("url"));
        $artwork->setDescription($request->get("description"));
        $artwork->setTitle($request->get("title"));


        $entityManager->flush();
        $jsonContent = $normalizer->normalize($artwork, 'json');

        return new Response("Art Updated Successfully " . json_encode($jsonContent));
    }
    #[Route('/artwork/delete/{id}')]
    public function deleteArt(EntityManagerInterface $entityManager, $id, Request $request, NormalizerInterface $normalizer): Response

    {
        $artwork =  $entityManager->getRepository(Artwork::class)->find($id);
        $entityManager->remove($artwork);
        $entityManager->flush();


        return new Response("Art Updated Removed ");
    }
    #[Route('/artwork/{id}')]
    public function Art(EntityManagerInterface $entityManager, Request $request, $id, NormalizerInterface $normalizer): Response

    {
        $artwork = $entityManager
            ->getRepository(Artwork::class)
            ->find($id);
        $jsonContent = $normalizer->normalize($artwork, 'json');
        return new Response(json_encode($jsonContent));
    }
    #[Route('/comments/artwork')]
    public function ArtComments(EntityManagerInterface $entityManager, Request $request, NormalizerInterface $normalizer): Response
    {

        $artwork =  $entityManager->getRepository(Artwork::class)->find($request->get("art"));

        $comments =  $entityManager->getRepository(Commentsoeuvre::class)->findByOeuvre($artwork);
        $jsonContent = $normalizer->normalize($comments, 'json');
        return new Response(json_encode($jsonContent));
    }
    #[Route('/comment/artwork/new')]
    public function NewArtComment(EntityManagerInterface $entityManager, Request $request, NormalizerInterface $normalizer): Response
    {

        $artwork =  $entityManager->getRepository(Artwork::class)->find($request->get("art"));
        $user =  $entityManager->getRepository(User::class)->find($request->get("user"));

        $comments = new Commentsoeuvre();
        $comments->setComment($request->get("comment"));
        $comments->setOeuvre($artwork);
        $comments->setUser($user);

        $entityManager->persist($comments);
        $entityManager->flush();
        $jsonContent = $normalizer->normalize($comments, 'json');

        return new Response("Comment added Successfully " . json_encode($jsonContent));
    }
    #[Route('/note/artwork')]
    public function noteArt(EntityManagerInterface $entityManager, Request $request, NormalizerInterface $normalizer): Response
    {
        $artwork =  $entityManager->getRepository(Artwork::class)->find($request->get("art"));

        $notes = $entityManager
            ->getRepository(Noteoeuvre::class)
            ->findByidOeuvre($artwork);
        $total = 0;
        $count = count($notes);

        foreach ($notes as $note) {
            $total += $note->getNote();
        }

        $average = $count > 0 ? $total / $count : 0;

        $date[] = [
            "note" => $average,
        ];
        $jsonContent = $normalizer->normalize($date, 'json');

        return new Response(json_encode($jsonContent));
    }
    #[Route('/vote/artwork')]
    public function Vote(EntityManagerInterface $entityManager, Request $request, NormalizerInterface $normalizer): Response
    {
        $artwork = $entityManager
            ->getRepository(Artwork::class)
            ->findOneByid($request->get('art'));
        $user = $entityManager
            ->getRepository(User::class)
            ->findOneByid($request->get('user'));
        $vote = $entityManager->getRepository(Noteoeuvre::class)->findOneBy([
            'idOeuvre' => $artwork,
            'idUser' => $user
        ]);
        if (!$vote) {
            $vote = new Noteoeuvre();
            $vote->setIdOeuvre($artwork);
            $vote->setIdUser($user);
        }
        $vote->setNote($request->get('note'));
        $entityManager->persist($vote);
        $entityManager->flush();
        $jsonContent = $normalizer->normalize($vote, 'json');

        return new Response(json_encode($jsonContent));
    }
    #[Route('/addusermobile', name: 'adduser', methods: ['GET', 'POST'])]
    public function adduser(Request $request, NormalizerInterface $normalizer, UserPasswordEncoderInterface $userPasswordEncoder)
    {



        $firstname = $request->query->get("firstName");
        $lastname = $request->query->get("lastName");
        $phone = $request->query->get("phone");

        $email = $request->query->get("email");
        $password = $request->query->get("password");
        $roles = $request->query->get("roles");


        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return new Response("email invalid");
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
        } catch (Exception $ex) {
            return new Response("exception " . $ex->getMessage());
        }
    }






    #[Route('/loginmobile', name: 'LoginMobile', methods: ['GET', 'POST'])]
    public function login(Request $request, UserPasswordEncoderInterface $encoder, NormalizerInterface $normalizer)
    {
        // Get the submitted email and password
        $email = $request->query->get("email");
        $password = $request->query->get("password");

        $userRepository = $this->getDoctrine()->getRepository(User::class);
        $user = $userRepository->findOneBy(['email' => $email]);
        if ($user) {
            $hashedPassword = sha1($password);

            if ($hashedPassword == $user->getPassword()) {
                $email = $user->getEmail();
                $welcomeMessage = "Welcome $email!";
                $serializer = $this->get('serializer');
                $formatted = $serializer->normalize($user);
                $responseArray = [
                    'welcomeMessage' => $welcomeMessage,
                    'user' => $formatted
                ];
                return new JsonResponse($responseArray);
            } else {
                return new Response("password not found");
            }
        } else {
            return new Response("user not found");
        }
    }



    #[Route('/editprofilemobile', name: 'EditMobile', methods: ['GET', 'POST'])]
    public function edit(Request $request, UserPasswordEncoderInterface $encoder, NormalizerInterface $normalizer)
    {
        $id = $request->get("id");
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(User::class)->find($id);

        if (!$user) {
            return new Response('User not found.');
        }

        $firstname = $request->query->get("firstName");
        if ($firstname) {
            $user->setFirstName($firstname);
        }

        $lastname = $request->query->get("lastName");
        if ($lastname) {
            $user->setLastName($lastname);
        }

        $phone = $request->query->get("phone");
        if ($phone) {
            $user->setPhone($phone);
        }

        $email = $request->query->get("email");
        if ($email) {
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return new Response("Invalid email address.");
            }
            $user->setEmail($email);
        }

        $password = $request->query->get("password");
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
        } catch (Exception $ex) {
            return new Response("Exception: " . $ex->getMessage());
        }
    }
    #[Route('/reservationJson', name: 'app_reservation_json')]
    public function indexJsone(EntityManagerInterface $entityManager, Request $request, NormalizerInterface $normalizer): Response
    {
        $user = $entityManager->getRepository(User::class)->find($request->get("user"));
        $reservation = $entityManager->getRepository(Reservation::class)->findBy(['id_participant' =>  $user]);;

        $reservationNormalises = $normalizer->normalize($reservation, 'json', ['groups' => "reservations"]);
        $json = json_encode($reservationNormalises);
        return  new Response($json);
    }

    #[Route('/addReservationJson')]
    public function addReservation(
        Request $request,
        ValidatorInterface $validator,
        UserRepository $rep,
        EventRepository $er,
        NormalizerInterface $Normalizer
    ): Response {

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

        $jsonContent = $Normalizer->normalize($reservation, 'json', ['groups' => "reservations"]);
        return new Response("La réservation a bien été ajoutee" . json_encode($jsonContent));
    }

    #[Route('/updateReservationJson/{id}')]
    public function updateReservationJson(Request $request, ReservationRepository $rr, NormalizerInterface $normalizer, ValidatorInterface $validator, $id): Response
    {

        $reservation = $rr->findOneById($id);

        if (!$reservation) {
            throw $this->createNotFoundException('La réservation avec l\'id ' . $id . ' n\'existe pas.');
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

        return new Response('La réservation a été mise à jour : ' . $json);
    }


    #[Route('/deleteReservationJson/{id}')]
    public function deleteReservationJson($id, ReservationRepository $rr): Response
    {
        $reservation = $rr->find($id);


        $em = $this->getDoctrine()->getManager();
        $em->remove($reservation);
        $em->flush();

        return new Response("La réservation a bien été supprimée");
    }
    #[Route('/eventJson', name: 'app_event_Json')]
    public function indexJson(EventRepository $er, NormalizerInterface $normalizer): Response
    {


        $events = $er->findAll();


        $eventNormalises = $normalizer->normalize($events, 'json', ['groups' => "events"]);
        $json = json_encode($eventNormalises);
        return  new Response($json);
    }
    #[Route('/reclamations-json/{id}')]
    public function getReclamationsJson(EntityManagerInterface $entityManager, $id, ReclamationRepository $reclamationRepository, NormalizerInterface $Normalizer): Response
    {
        $user = $entityManager->getRepository(User::class)->find($id);
        $reclamations = $reclamationRepository->findByOwnerid($user);
        $jsonContent = $Normalizer->normalize($reclamations, 'json', ['groups' => "reclamations"]);

        return new Response(json_encode($jsonContent));
    }



    #[Route('/addReclamationJson')]
    public function addReclamation(EntityManagerInterface $entityManager, Request $request, ValidatorInterface $validator, UserRepository $rep, TypeReclamationRepository $r, NormalizerInterface $Normalizer): Response
    {
        // Récupérer les paramètres depuis la requête
        $title = $request->get('title');
        $description = $request->get('description');
        $user = $entityManager->getRepository(User::class)->find($request->get('user'));

        // Créer une nouvelle réclamation avec les paramètres
        $reclamation = new Reclamation();
        $reclamation->setTitle($title);
        $reclamation->setDescription($description);
        $reclamation->setDateCreation(new \DateTime());
        $reclamation->setEtat('on hold');
        $reclamation->setNote(0);
        $reclamation->setDateTreatment(new \DateTime());
        $reclamation->setTypereclamation($r->findOneBy([]));
        $reclamation->setOwnerid($user);
        // $form = $this->createForm(ReclamationFormType::class, $reclamation);
        // $form->handleRequest($request);

        // if ($request->isMethod('POST')) {
        //     $jsonData = $request->getContent();
        //     $data = json_decode($jsonData, true);
        //     $form->submit($data);

        //     if ($form->isValid()) {
        //         // affecter la date de création ,l'état et la note
        //         $reclamation->setDateCreation(new \DateTime());
        //         $reclamation->setEtat('on hold');
        //         $reclamation->setNote(0);
        //         $reclamation->setDateTreatment(new \DateTime());
        //         //récupérer l'utilisateur ayant l'id 155 mel bd, natetha lel ownerid 
        //         $user = $rep->find(155);
        //         //$user = $this->getUser(); normalement hedhi pour recuper user connecté 
        //         $reclamation->setOwnerid($user);

        //         $errors = $validator->validate($reclamation);
        //         if (count($errors) > 0) {
        //             $errors = $this->getErrorsFromForm($form);
        //             return new JsonResponse(['errors' => $errors], JsonResponse::HTTP_BAD_REQUEST);
        //         } else {
        //             //Action d'ajout
        $em = $this->getDoctrine()->getManager();
        $em->persist($reclamation);
        $em->flush();
        //             return new JsonResponse(['success' => true, 'message' => 'La réclamation a bien été enregistrée.'], JsonResponse::HTTP_CREATED);
        //         }



        // }

        $jsonContent = $Normalizer->normalize($reclamation, 'json', ['Groups' => "reclamations"]);
        return new Response("La réclamation a bien été ajoutee" . json_encode($jsonContent));
    }

    private function getErrorsFromForm(FormInterface $form): array
    {
        $errors = [];
        foreach ($form->getErrors(true, true) as $error) {
            $errors[$error->getOrigin()->getName()] = $error->getMessage();
        }
        foreach ($form->all() as $childForm) {
            if ($childForm instanceof FormInterface && $childErrors = $this->getErrorsFromForm($childForm)) {
                $errors[$childForm->getName()] = $childErrors;
            }
        }
        return $errors;
    }

    #[Route('/updateR/{id}')]
    public function updateR($id, ReclamationRepository $rep, UserRepository $ru, TypeReclamationRepository $r, ManagerRegistry $doctrine, Request $request, NormalizerInterface $Normalizer): Response
    {
        $reclamation = $rep->find($id);
        $title = $request->get('title');
        $description = $request->get('description');
        $reclamation->setTitle($title);
        $reclamation->setDescription($description);
        $reclamation->setDateCreation(new \DateTime());
        $reclamation->setEtat('on hold');
        $reclamation->setNote(0);
        $reclamation->setDateTreatment(new \DateTime());
        $reclamation->setTypereclamation($r->findOneBy([]));

        // Action de Mise à jour
        $em = $doctrine->getManager();
        $em->flush();

        $jsonContent = $Normalizer->normalize($reclamation, 'json', ['Groups' => "reclamations"]);
        return new Response("La réclamation a bien été modifiee" . json_encode($jsonContent));
    }



    #[Route('/deleteR/{id}')]
    public function deleteR($id, ReclamationRepository $rep, ManagerRegistry $doctrine, NormalizerInterface $Normalizer): Response
    {
        // récupérer la réclamation à supprimer
        $reclamation = $rep->find($id);

        // // vérifier si la réclamation existe
        // if (!$reclamation) {
        //     return new JsonResponse(['success' => false, 'message' => 'La réclamation n\'existe pas.'], JsonResponse::HTTP_NOT_FOUND);
        // }

        // supprimer la réclamation
        $em = $doctrine->getManager();
        $em->remove($reclamation);
        $em->flush();
        $jsonContent = $Normalizer->normalize($reclamation, 'json', ['Groups' => "reclamations"]);


        // retourner une réponse indiquant que la suppression a réussi
        return new Response("La réclamation a bien été supprimée" . json_encode($jsonContent));
    }

    #[Route('/updateNoteReclamation/{id}')]
    public function updateNote($id, ReclamationRepository $rep, ManagerRegistry $doctrine, Request $request, NormalizerInterface $Normalizer): Response
    {
        // récupérer la classe à modifier
        $reclamation = $rep->find($id);

        // Récupérer les paramètres depuis la requête

        $note = $request->get('note');
        //



        $reclamation->setDateCreation(new \DateTime());
        $reclamation->setEtat('on hold');
        $reclamation->setNote($note);
        $reclamation->setDateTreatment(new \DateTime());
        // Action de Mise à jour
        $em = $doctrine->getManager();
        $em->flush();

        $jsonContent = $Normalizer->normalize($reclamation, 'json', ['Groups' => "reclamations"]);
        return new Response("La note a bien été modifiee" . json_encode($jsonContent));
    }
    #[Route('/type-json', name: 'app_type_json')]
    public function getTypeReclamationsJson(TypeReclamationRepository $r, NormalizerInterface $Normalizer): Response
    {
        $types = $r->findAll();
        $jsonContent = $Normalizer->normalize($types, 'json', ['Groups' => "types"]);

        return new Response(json_encode($jsonContent));
    }
    #[Route('/getall')]
    public function stationb(EntityManagerInterface $entityManager, NormalizerInterface $serializer): Response
    {
        $dons = $entityManager->getRepository(Donation::class)->findAll();

        $snorm = $serializer->normalize($dons, 'json');
        $json = json_encode($snorm);
        return new Response($json);
    }
    #[Route('/readBlogJSON', name: 'app_read_blog_json')]
    public function readBlog(EntityManagerInterface $entityManager, NormalizerInterface $normalizer): Response
    {
        //récupérer le repository 
        $blogs = $entityManager->getRepository(Blog::class)->findAll();


        $jsonContent = $normalizer->normalize($blogs, 'json', ['groups' => "blogs"]);
        return new Response(json_encode($jsonContent));
    }
}
