<?php

namespace App\Controller;

use App\Repository\CategorieEventRepository;
use App\Form\CategorieEventType;
use App\Entity\CategorieEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;

class CategorieEventController extends AbstractController
{    #[Route('/categorie/event', name: 'app_categorie_event')]
    public function index(): Response
    {
    $categories = $entityManager
            ->getRepository(CategorieEvent::class)
            ->findAll();

        return $this->render('categorie_event/index.html.twig', [
            'categorie' => 'categories',
        ]);
    
}


#[Route('/readCategorie', name: 'app_readCategorie')]
public function readCategorie(CategorieEventRepository $ce): Response
{
    
    //Utiliser findAll()
    $categories = $ce->findAll();
    return $this->render('categorie_event/AffichageCategorie.html.twig', [
        'categorie' => $categories,
    ]);
}


#[Route('/deleteCategorie/{id}', name: 'app_deleteCategrie')]
public function deleteCategorie($id, CategorieEventRepository $ce, 
ManagerRegistry $doctrine): Response
{
    //récupérer la classe à supprimer
    $categories=$ce->find($id);
    if (!$categories) {
        throw $this->createNotFoundException('Categorie not found for id '.$id);
    }
    //Action de suppression
    //récupérer l'Entitye manager
    $em=$doctrine->getManager();
    $em->remove($categories);
    //La maj au niveau de la bd
    $em->flush();
    return $this->redirectToRoute('app_readCategorie');
}

#[Route('/addCategorie', name: 'app_addCategorie')]
public function addCategorie(ManagerRegistry $doctrine,
Request $request)
{
$categories= new CategorieEvent();
$form=$this->createForm(CategorieEventType::class,$categories);
               $form->handleRequest($request);
               if($form->isSubmitted()){
                //Action d'ajout
                   $em =$doctrine->getManager() ;
                   $em->persist($categories);
                   $em->flush();
        return $this->redirectToRoute("app_readCategorie");
    }
return $this->renderForm("categorie_event/createCategorie.html.twig",
                   array("f"=>$form));
               }

               
               #[Route('/updateCategorie/{id}', name: 'app_updateCategorie')]
               public function updateCategorie($id, EventRepository $ce, ManagerRegistry $doctrine, Request $request)
               {
                   //récupérer la classe à modifier
                   $categories = $ce->find($id);
                   $form = $this->createForm(CategorieEventType::class,$categories);
                   $form->handleRequest($request);
                   if($form->isSubmitted()){
                       //Action de MAJ
                       $em =$doctrine->getManager() ;
                       $em->flush(); // modification de cette ligne
                       return $this->redirectToRoute("app_readCategorie");
                   }
                   return $this->renderForm("categorie_event/createCategorie.html.twig", array("f"=>$form));
               }

}

