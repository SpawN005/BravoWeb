<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Form\CategorieType;
use App\Repository\CategorieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;

class CategorieController extends AbstractController
{
    #[Route('/categorie', name: 'app_categorie')]
    public function index(): Response
    {
        return $this->render('categorie/index.html.twig', [
            'controller_name' => 'CategorieController',
        ]);
    }

    #[Route('/readCategorie', name: 'app_readCategorie')]
    public function readCategorie(CategorieRepository $categorieRepository): Response
    {
        // Utiliser findAll()
        $categories = $categorieRepository->findAll();
        return $this->render('categorie/AffichageCategorie.html.twig', [
            'c' => $categories,
        ]);
    }

    #[Route('/deleteCategorie/{id}', name: 'app_deleteCategorie')]
    public function deleteCategorie($id, CategorieRepository $rep, ManagerRegistry $doctrine): Response
    {
        // Récupérer la catégorie à supprimer
        $categorie = $rep->find($id);
        if (!$categorie) {
            throw $this->createNotFoundException('Categorie not found for id '.$id);
        }
        // Action de suppression
        // Récupérer l'EntityManager
        $em = $doctrine->getManager();
        $em->remove($categorie);
        // La maj au niveau de la bd
        $em->flush();
        return $this->redirectToRoute('app_readCategorie');
    }

    #[Route('/addCategorie', name: 'app_addCategorie')]
    public function addCategorie(ManagerRegistry $doctrine, Request $request)
    {
        $categorie = new Categorie();
        $form = $this->createForm(CategorieType::class, $categorie);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            // Action d'ajout
            $em = $doctrine->getManager();
            $em->persist($categorie);
            $em->flush();
            return $this->redirectToRoute("app_readCategorie");
        }
        return $this->renderForm("categorie/createCategorie.html.twig", array("f"=>$form));
    }

    #[Route('/updateCategorie/{id}', name: 'app_updateCategorie')]
    public function updateCategorie($id, CategorieRepository $categorieRepository, ManagerRegistry $doctrine, Request $request)
    {
        // Récupérer la catégorie à modifier
        $categorie = $categorieRepository->find($id);
        $form = $this->createForm(CategorieType::class, $categorie);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            // Action de MAJ
            $em = $doctrine->getManager();
            $em->flush();
            return $this->redirectToRoute("app_readCategorie");
        }
        return $this->renderForm("categorie/createCategorie.html.twig", array("f"=>$form));
    }
}
