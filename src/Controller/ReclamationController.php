<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\ReclamationFormType;
use App\Entity\Reclamation;
use App\Entity\Typereclamation;
use App\Entity\User;
use App\Repository\ReclamationRepository;
use App\Repository\UserRepository;
use App\Form\SearchReclamationFormType;
use App\Form\AvisReclamationFormType;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Knp\Snappy\Pdf;



use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;






#[Route('/reclamation')]
class ReclamationController extends AbstractController
{
    #[Route('/reclamation', name: 'app_reclamation')]
    public function index(): Response
    {
        return $this->render('reclamation/index.html.twig', [
            'controller_name' => 'ReclamationController',
        ]);
    }
// affichage des reclamations avec filtrage selon etat cote admin 
    #[Route('/readReclamation/{etat}', name: 'app_readR', methods: ['GET'])]
public function readReclamation(ReclamationRepository $r, $etat = null,Request $request): Response
{
    $reclamations = [];
    $etat = $request->query->get('etat');
    if ($etat) {
        // Si un état est sélectionné, filtrer les réclamations 
        $reclamations = $r->findBy(['etat' => $etat]);
    } else {
        // Sinon, récupérer toutes les réclamations
        $reclamations = $r->findAll();
    }
    
    return $this->render('reclamation/readR.html.twig', [
        'reclamations' => $reclamations,
    ]);
}


    #[Route('/addReclamation', name: 'app_addR',methods: ['GET','POST'])]
    public function addReclamation(ManagerRegistry $doctrine,
    Request $request, ValidatorInterface $validator,UserRepository $rep)
{
    $reclamation= new Reclamation();
$form=$this->createForm(ReclamationFormType::class,$reclamation);
                   $form->handleRequest($request);
                   if($form->isSubmitted()&& $form->isValid()){
        
        // affecter la date de création ,l'état et la note
        $reclamation->setDateCreation(new \DateTime());
        $reclamation->setEtat('on hold');
        $reclamation->setNote(0);
        $reclamation->setDateTreatment(new \DateTime());
       //récupérer l'utilisateur ayant l'id 155 mel bd, natetha lel ownerid 
        $user=$rep->find(155);
       // $user = $this->getUser(); normalement hedhi pour recuper user connecté 
        $reclamation->setOwnerid($user);
        
        $errors = $validator->validate($reclamation);
        if (count($errors) > 0) {
            $this->addFlash('error', 'Les données soumises ne sont pas valides');
            return $this->redirectToRoute('app_addR');
        }else {
                    //Action d'ajout
                       $em =$doctrine->getManager() ;
                       $em->persist($reclamation);
                       $em->flush();
                       $this->addFlash('success', 'La réclamation a bien été enregistrée.');
                       //return $this->redirectToRoute("reclamation_user");
                       }
        }
        //L'envoi du formulaire à la page twig
    return $this->renderForm("reclamation/addR.html.twig",
    [
        'reclamation' => $reclamation,
        'form' => $form,
    ]);
                   }

  #[Route('/deleteR/{id}', name: 'app_deleteR')]
     public function deleteR($id , ReclamationRepository $rep, ManagerRegistry $doctrine): Response
        {
            //recuperer la classe a supprimer 
            $reclamation=$rep->find($id);
            //action de suppression
            $em=$doctrine->getManager();
            $em->remove($reclamation);
            $em->flush();// pour la mise a jour f bd 
            $this->addFlash('success', 'La réclamation a bien été supprimée.');
            return $this->redirectToRoute('app_readR',['etat' => 'on_hold'] );
                }
     #[Route('/updateR/{id}', name: 'app_updateR')]
         public function updateR($id,ReclamationRepository $rep,
         ManagerRegistry $doctrine,Request $request)
         {
            //récupérer la classe à modifier
                $reclamation=$rep->find($id);
                if (!$reclamation) {
                    throw $this->createNotFoundException('La réclamation n\'existe pas.');
                }
                $form=$this->createForm(ReclamationFormType::class,$reclamation);
                $form->handleRequest($request);
                if($form->isSubmitted()&& $form->isValid()){
                    //Action de Mise a jour 
                        $em =$doctrine->getManager() ;
                        $em->flush();
                        $this->addFlash('success', 'La réclamation a bien été modifiée.');
                        return $this->redirectToRoute("app_readR",['etat' => 'on_hold']);
                                     }
                return $this->renderForm("reclamation/updateR.html.twig",
                                                    array("form"=>$form));
     } 
     #[Route('/searchReclamationBytitle', name: 'searchReclamationBytitle')]  
     public function searchReclamation(Request $request, ReclamationRepository $reclamationRepository)
     {
         $title = null; 
         $reclamations = []; // Définir une valeur par défaut  s'il n'y a pas de résultat de recherche
         $searchForm = $this->createForm(SearchReclamationFormType::class);
         $searchForm->handleRequest($request);
         if ($searchForm->isSubmitted() && $searchForm->isValid()) {
             $title = $searchForm->get('title')->getData();
             if ($title !== null) {
                 $resultOfSearch = $reclamationRepository->findByTitle($title);
                 return $this->renderForm('reclamation/search.html.twig', [
                     'Reclamations'=>$resultOfSearch,
                     'searchReclamationBytitle' => $searchForm,
                 ]);
             }
         }
     
         $reclamations = $reclamationRepository->findAll();
         return $this->renderForm('reclamation/search.html.twig', [
             'Reclamations' => $reclamations,
             'searchReclamationBytitle' => $searchForm,
             'title' => $title,
         ]);
     }
     //affichege des reclamations coté user (ses propres reclam)
     #[Route('/UserReclamation', name: 'reclamation_user')]  
     public function userReclamations(ReclamationRepository $r){
       // $user = $this->getUser(); // récupérer l'utilisateur connecté
       // $ownerId = $user->getId(); // récupérer l'id de l'utilisateur connecté
       // $reclamations = $userRepository->find($ownerId)->getReclamations(); // récupérer les réclamations de l'utilisateur
        $reclamations = $r->findBy(['ownerid' => 155]);

        return $this->render('reclamation/readUserR.html.twig', [
            'reclamations' => $reclamations,
        ]);

     }


     #[Route('/avisReclamation/{id}', name: 'reclamation_avis')]  
     public function avis($id, Request $request, ReclamationRepository $rep, EntityManagerInterface $entityManager)
     {
         $reclamation = $rep->find($id);
         // $reclamation = $rep->findOneBy(['ownerid' => 155]);
     
         $form = $this->createForm(AvisReclamationFormType::class);
         $form->handleRequest($request);
     
         if ($form->isSubmitted() && $form->isValid()) {
             $avis = $form->getData();
             
             // Mettre à jour l'état de la réclamation
             $etat = $avis['satisfait'] ? 'treated' : 'processing';
             $entityManager->createQuery('UPDATE App\Entity\Reclamation r SET r.etat = :etat WHERE r.id = :id')
                           ->setParameter('etat', $etat)
                           ->setParameter('id', $id)
                           ->execute();
            // mettre a jour la note 
            $note = $avis['Note'];
            $entityManager->createQuery('UPDATE App\Entity\Reclamation r SET r.note = :note WHERE r.id = :id')
                          ->setParameter('note', $note)
                          ->setParameter('id', $id)
                          ->execute();

             return $this->redirectToRoute('reclamation_user');
         }
     
         return $this->render('reclamation/avis.html.twig', [
             'reclamation' => $reclamation,
             'form' => $form->createView(),
         ]);
     }

     #[Route('/traiterReclamation/{id}', name: 'reclamation_traiter')]
    // #[IsGranted(Role_Admin)] 
     public function traiter(Request $request, Reclamation $reclamation,UserRepository $rep){
        // Mettre à jour l'état de la réclamation
    $reclamation->setEtat('processing');
    $reclamation->setDateTreatment(new \DateTime());

    $entityManager = $this->getDoctrine()->getManager();
    $entityManager->persist($reclamation);
    $entityManager->flush();

    // Envoyer une notification au simple utilisateur
    $user=$rep->find(155);
   // $user = $reclamation->getUser();// hedhi mbaed selon user connecte
    $message = "Votre réclamation avec l'identifiant ".$reclamation->getId()." a été mise en cours de traitement.";

    $session = $request->getSession();
    $session->getFlashBag()->add('success', $message);

    // Rediriger l'administrateur vers la liste des réclamations
    return $this->redirectToRoute('app_readR',['etat' => 'on_hold']);
     }

     //fonction associé au bundle de generation de pdf 
     private $pdf;

     public function __construct(Pdf $pdf)
     {
         $this->pdf = $pdf;
     }
     #[Route('/genererPdf', name: 'genererPdf')]
     public function generatePdfAction(ReclamationRepository $r)
     {
         $reclamations = $r->findAll();
     
         $html = $this->renderView('reclamation/pdf.html.twig', [
             'reclamations' => $reclamations,
         ]);
     
         $filename = 'reclamations.pdf';
     
         return new PdfResponse(
             $this->pdf->getOutputFromHtml($html),
             $filename,
             'application/pdf',
             'inline'
         );
     }



     
 
 
   






     

}