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
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

use Dompdf\DompdfBundle\DompdfBundle;
use Dompdf\Dompdf;
use Dompdf\Options;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\PieChart;
use CMEN\GoogleChartsBundle\GoogleCharts\Chart;
use CMEN\GoogleChartsBundle\GoogleCharts\EventType;
use CMEN\GoogleChartsBundle\GoogleCharts\Options\ChartOptionsInterface;
use CMEN\GoogleChartsBundle\GoogleCharts\Options\PieChart\PieChartOptions;
use Knp\Component\Pager\PaginatorInterface;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\LineChart;









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
public function readReclamation(ReclamationRepository $r, $etat = null,Request $request,PaginatorInterface $paginator): Response
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
    // Paginer les résultats
    $pagination = $paginator->paginate(
        $reclamations,
        $request->query->getInt('page', 1),
        10 // nombre de résultats par page
    );
    
    return $this->render('reclamation/readR.html.twig', [
        'reclamations' => $pagination,
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
            return $this->redirectToRoute('reclamation_user' );
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
                        return $this->redirectToRoute("reclamation_user");
                                     }
                return $this->renderForm("reclamation/updateR.html.twig",
                                                    array("form"=>$form));
     } 

     //methode de recherche multicriteres titre +etat+datecreation
     #[Route('/searchReclamationBytitle', name: 'searchReclamationBytitle')]  
     public function searchReclamation(Request $request, ReclamationRepository $reclamationRepository)
     {
         $title = null;
         $etat = null;
         $date = null;
         $reclamations = []; // Définir une valeur par défaut s'il n'y a pas de résultat de recherche
         $searchForm = $this->createForm(SearchReclamationFormType::class);
         $searchForm->handleRequest($request);
         if ($searchForm->isSubmitted() && $searchForm->isValid()) {
             $title = $searchForm->get('title')->getData();
             $etat = $searchForm->get('etat')->getData();
             $date = $searchForm->get('dateCreation')->getData();
     
             // Appeler la méthode de recherche avec les paramètres
             $resultOfSearch = $reclamationRepository->findByTitleAndStateAndCreationDate($title, $etat, $date);
     
             return $this->renderForm('reclamation/search.html.twig', [
                 'Reclamations' => $resultOfSearch,
                 'searchReclamationBytitle' => $searchForm,
             ]);
         }
     
         // Afficher toutes les réclamations si aucun paramètre n'a été saisi
         $reclamations = $reclamationRepository->findAll();
     
         return $this->renderForm('reclamation/search.html.twig', [
             'Reclamations' => $reclamations,
             'searchReclamationBytitle' => $searchForm,
             'title' => $title,
             'etat' => $etat,
             'date' => $date
         ]);
     }
     
           
     //affichage des reclamations coté user (ses propres reclam)
     #[Route('/UserReclamation', name: 'reclamation_user')]  
     public function userReclamations(ReclamationRepository $r,PaginatorInterface $paginator,Request $request){
       // $user = $this->getUser(); // récupérer l'utilisateur connecté
       // $ownerId = $user->getId(); // récupérer l'id de l'utilisateur connecté
       // $reclamations = $userRepository->find($ownerId)->getReclamations(); // récupérer les réclamations de l'utilisateur
        $reclamations = $r->findBy(['ownerid' => 155]);
        $pagination = $paginator->paginate(
            $reclamations,
            $request->query->getInt('page', 1), // numéro de la page
            10 // nombre d'éléments par page
        );

        return $this->render('reclamation/readUserR.html.twig', [
            'reclamations' => $pagination,
        ]);

     }

     //methode donner avis cote user 
     #[Route('/avisReclamation/{id}', name: 'reclamation_avis')]  
public function avis($id, Request $request, ReclamationRepository $rep, EntityManagerInterface $entityManager, SessionInterface $session)
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

        // Message flash pour indiquer que la mise à jour a été effectuée avec succès
        $message = 'Votre avis a été enregistré avec succès.';
        $session->getFlashBag()->add('success', $message);

        // Message flash pour indiquer si le user est satisfait ou non satisfait
        $satisfaction = $avis['satisfait'] ? 'satisfait' : 'non satisfait';
        $message = 'Le user est ' . $satisfaction . " Pour la reclamation ayant id ".$reclamation->getId().'.';
        $session->getFlashBag()->add('satisfaction', $message);

        // Message flash pour indiquer si le user a donné une note
        if ($note) {
            $message = 'Le user a donné une note de ' . $note ." Pour la reclamation ayant id ".$reclamation->getId().'.';
            $session->getFlashBag()->add('note', $message);
        }

        return $this->redirectToRoute('reclamation_user');
    }

    return $this->render('reclamation/avis.html.twig', [
        'reclamation' => $reclamation,
        'form' => $form->createView(),
    ]);
}

//methode de traitement de reclam cote admin 
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

//methode de generation de pdf 
     #[Route('/genererPdf', name: 'genererPdf')]
     public function imprime(ReclamationRepository $repository): Response
     {
         $pdfOptions = new Options();
         $pdfOptions->set('defaultFont', 'Arial');
         $dompdf = new Dompdf($pdfOptions);
         $reclamations= $repository->findAll();
         $html = $this->renderView('reclamation/pdf.html.twig', [
             'reclamations' => $reclamations,
         ]);
         $dompdf->loadHtml($html);
         $dompdf->setPaper('A4', 'portrait');
         $dompdf->render();
         $dompdf->stream("Liste des reclamations.pdf", [
             "Attachment" => true
         ]);
         return $this->redirectToRoute('app_readR');
     }

     //generer des stats selon etat piechart
#[Route('/genererstats', name: 'genererstats')]

public function stats (Request $request ,ReclamationRepository $reclamationRepository): Response
    {
        $rec=$reclamationRepository->findAll();
        $r1=0;
        $r2=0;
        $r3=0;
        foreach ($rec as $reclamation) {
            if ($reclamation->getEtat() == 'on hold') {
                $r1 += 1;
            } elseif ($reclamation->getEtat() == 'processing') {
                $r2 += 1;
            } else {
                $r3 += 1;
            }
              // Ajoutez la date courante et le total actuel à vos tableaux
        $dates[] = $reclamation->getDateCreation(); 
        $totals[] = $r1 + $r2 + $r3;

        }
        // pour le graphique en secteur selon etat 
        $pieChart = new PieChart();
        $pieChart->getData()->setArrayToDataTable(
            [['etat', 'nombre'],
                ['on hold', $r1],
                ['processing', $r2],
                ['treated', $r3],
            ]
        );
        $pieChart->getOptions()->setTitle('Nombre de reclamation par etat');
        $pieChart->getOptions()->getTitleTextStyle()->setColor('#FFFFFF');
        $pieChart->getOptions()->getTitleTextStyle()->setBold(true);
        $pieChart->getOptions()->getLegend()->getTextStyle()->setColor('#FFFFFF');
        $pieChart->getOptions()->getTitleTextStyle()->setItalic(true);
        $pieChart->getOptions()->getTitleTextStyle()->setFontName('Arial');
        $pieChart->getOptions()->getTitleTextStyle()->setFontSize(20);
        $pieChart->getOptions()->setHeight(300);
        $pieChart->getOptions()->setWidth(600);
        $pieChart->getOptions()->setBackgroundColor('#000000'); // noir
        $pieChart->getOptions()->setColors(['#FF0000', '#00FF00', '#0000FF']);

         // pour le graphe en line chart evolution du nb de reclam
    $lineChart = new LineChart();
    $lineChart->getData()->setArrayToDataTable(
        [['Date', 'Total réclamations']]
        + array_map(null, $dates, $totals)
    );
    $lineChart->getOptions()->setTitle('Évolution du nombre total de réclamations');
    $lineChart->getOptions()->getTitleTextStyle()->setBold(true);
    $lineChart->getOptions()->getTitleTextStyle()->setItalic(true);
    $lineChart->getOptions()->getTitleTextStyle()->setFontName('Arial');
    $lineChart->getOptions()->getTitleTextStyle()->setFontSize(20);
    $lineChart->getOptions()->getTitleTextStyle()->setColor('#FFFFFF');
    $lineChart->getOptions()->setHeight(300);
    $lineChart->getOptions()->setWidth(600);
    $lineChart->getOptions()->setBackgroundColor('#000000'); // noir
    $lineChart->getOptions()->getLegend()->getTextStyle()->setColor('#FFFFFF');
    $lineChart->getOptions()->setColors(['#FF0000', '#00FF00', '#0000FF']);

  
        return $this->render('reclamation/stats.html.twig', [
            'reclamations' => $reclamation,'piechart' => $pieChart,'linechart' => $lineChart
        ]);
    }





     
     

     



     
 
 
   






     

}
