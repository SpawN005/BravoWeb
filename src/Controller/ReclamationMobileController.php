<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use App\Repository\ReclamationRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Repository\UserRepository;
use App\Entity\Reclamation;
use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\ReclamationFormType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use App\Repository\TypeReclamationRepository;





class ReclamationMobileController extends AbstractController
{
    #[Route('/reclamation/mobile', name: 'app_reclamation_mobile')]
    public function index(): Response
    {
        return $this->render('reclamation_mobile/index.html.twig', [
            'controller_name' => 'ReclamationMobileController',
        ]);
    }

    #[Route('/reclamations-json', name: 'app_reclamations_json')]
public function getReclamationsJson(ReclamationRepository $reclamationRepository, NormalizerInterface $Normalizer): Response
{
    $reclamations = $reclamationRepository->findAll();
    $jsonContent = $Normalizer->normalize($reclamations, 'json',['Groups'=>"reclamations"]);

    return new Response(json_encode($jsonContent));
}



#[Route('/addReclamationJson', name: 'app_addR')]
public function addReclamation(Request $request, ValidatorInterface $validator,UserRepository $rep,TypeReclamationRepository $r ,NormalizerInterface $Normalizer): Response
{
     // Récupérer les paramètres depuis la requête
     $title = $request->get('title');
     $description = $request->get('description');

     
     // Créer une nouvelle réclamation avec les paramètres
     $reclamation = new Reclamation();
     $reclamation->setTitle($title);
     $reclamation->setDescription($description);
     $reclamation->setDateCreation(new \DateTime());
     $reclamation->setEtat('on hold');
     $reclamation->setNote(0);
     $reclamation->setDateTreatment(new \DateTime());
     $reclamation->setTypereclamation($r->findOneBy([]));
    $reclamation->setOwnerid($rep->findOneBy([]));
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

    $jsonContent = $Normalizer->normalize($reclamation, 'json',['Groups'=>"reclamations"]);
    return new Response("La réclamation a bien été ajoutee". json_encode($jsonContent) );
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

#[Route('/updateR/{id}', name: 'app_updateR')]
public function updateR($id, ReclamationRepository $rep,UserRepository $ru,TypeReclamationRepository $r, ManagerRegistry $doctrine, Request $request, NormalizerInterface $Normalizer): Response
{
    $reclamation=$rep->find($id);
$title = $request->get('title');
$description = $request->get('description');
$reclamation->setTitle($title);
$reclamation->setDescription($description);
$reclamation->setDateCreation(new \DateTime());
$reclamation->setEtat('on hold');
$reclamation->setNote(0);
$reclamation->setDateTreatment(new \DateTime());
$reclamation->setTypereclamation($r->findOneBy([]));
    $reclamation->setOwnerid($ru->findOneBy([]));
        // Action de Mise à jour
        $em = $doctrine->getManager();
        $em->flush();

        $jsonContent = $Normalizer->normalize($reclamation, 'json',['Groups'=>"reclamations"]);
        return new Response("La réclamation a bien été modifiee". json_encode($jsonContent) );
    }



#[Route('/deleteR/{id}', name: 'app_deleteR')]
public function deleteR($id, ReclamationRepository $rep, ManagerRegistry $doctrine,NormalizerInterface $Normalizer): Response
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
    $jsonContent = $Normalizer->normalize($reclamation, 'json',['Groups'=>"reclamations"]);


    // retourner une réponse indiquant que la suppression a réussi
    return new Response("La réclamation a bien été supprimée". json_encode($jsonContent) );
}

#[Route('/updateNote/{id}', name: 'app_updateR')]
public function updateNote($id, ReclamationRepository $rep, ManagerRegistry $doctrine, Request $request, NormalizerInterface $Normalizer): Response
{
    // récupérer la classe à modifier
    $reclamation = $rep->find($id);
    
// Récupérer les paramètres depuis la requête
$title = $request->get('title');
$description = $request->get('description');
$note = $request->get('note');
//


$reclamation->setTitle($title);
$reclamation->setDescription($description);
$reclamation->setDateCreation(new \DateTime());
$reclamation->setEtat('on hold');
$reclamation->setNote($note);
$reclamation->setDateTreatment(new \DateTime());
        // Action de Mise à jour
        $em = $doctrine->getManager();
        $em->flush();

        $jsonContent = $Normalizer->normalize($reclamation, 'json',['Groups'=>"reclamations"]);
        return new Response("La note a bien été modifiee". json_encode($jsonContent) );
    }






}
