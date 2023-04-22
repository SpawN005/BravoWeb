<?php

namespace App\Controller;

use App\Repository\EventCategorieRepository;
use App\Form\EventCategorieType;
use App\Entity\EventCategorie;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;

class EventCategorieController extends AbstractController
{
    #[Route('/categorie/event', name: 'app_event_categorie')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $categories = $entityManager
            ->getRepository(EventCategorie::class)
            ->findAll();

        return $this->render('event_categorie/index.html.twig', [
            'categories' => $categories,
        ]);
    }

    // #[Route('/readCategorie', name: 'app_readCategorie')]
    // public function readCategorie(EventCategorieRepository $ce): Response
    // {

    //Utiliser findAll()
    //     $categories = $ce->findAll();
    //     return $this->render('event_categorie/AffichageCategorie.html.twig', [
    //         'categorie' => $categories,
    //     ]);
    // }

    #[Route('/deleteCategorie/{id}', name: 'app_deleteCategrie')]
    public function deleteCategorie($id, EventCategorieRepository $ce, ManagerRegistry $doctrine): Response
    {
        //récupérer la classe à supprimer
        $categories = $ce->find($id);
        if (!$categories) {
            throw $this->createNotFoundException('Categorie not found for id '.$id);
        }
        //Action de suppression
        //récupérer l'Entitye manager
        $em = $doctrine->getManager();
        $em->remove($categories);
        //La maj au niveau de la bd
        $em->flush();
        return $this->redirectToRoute('app_event_categorie');
    }

    #[Route('/addCategorie', name: 'app_addCategorie')]
    public function addCategorie(ManagerRegistry $doctrine, Request $request): Response
    {
        $categories = new EventCategorie();
        $form = $this->createForm(EventCategorieType::class,$categories);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            //Action d'ajout
            $em = $doctrine->getManager();
            $em->persist($categories);
            $em->flush();
            return $this->redirectToRoute("app_event_categorie");
        }

        return $this->renderForm("event_categorie/createCategorie.html.twig", [
            "form" => $form
        ]);
    }

    #[Route('/updateCategorie/{id}', name: 'app_updateCategorie')]
    public function updateCategorie($id, EventCategorieRepository $ce, ManagerRegistry $doctrine, Request $request): Response
    {
        //récupérer la classe à modifier
        $categories = $ce->find($id);
        $form = $this->createForm(EventCategorieType::class,$categories);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            //Action de MAJ
            $em =$doctrine->getManager();
            $em->flush();
            return $this->redirectToRoute("app_event_categorie");
        }
        return $this->renderForm("event_categorie/createCategorie.html.twig", [
            "form" => $form
        ]);
    }
}