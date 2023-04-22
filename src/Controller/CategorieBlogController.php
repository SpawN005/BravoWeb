<?php

namespace App\Controller;

use App\Repository\CategorieBlogRepository;
use App\Form\CategorieBlogType;
use App\Entity\CategorieBlog;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;


class CategorieBlogController extends AbstractController
{
    #[Route('/categorie/blog', name: 'app_categorie_blog')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $categories = $entityManager
            ->getRepository(CategorieBlog::class)
            ->findAll();

        return $this->render('categorie_blog/index.html.twig', [
            'categories' => $categories,
        ]);
    }

    #[Route('/new', name: 'app_new_categorie_blog')]
    public function addCategorie(ManagerRegistry $doctrine, Request $request): Response
    {
        $categories = new CategorieBlog();
        $form = $this->createForm(CategorieBlogType::class,$categories);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            //Action d'ajout
            $em = $doctrine->getManager();
            $em->persist($categories);
            $em->flush();
            return $this->redirectToRoute("app_categorie_blog");
        }

        return $this->renderForm("categorie_blog/addCategorieBlog.html.twig", [
            "f" => $form
        ]);
    }


    #[Route('/deleteCategorie/{id}', name: 'app_delete_categorie')]
    public function deleteBlog($id, CategorieBlogRepository $rep, 
    ManagerRegistry $doctrine): Response
    {
        //récupérer la classe à supprimer
        $categories=$rep->find($id);
        //Action de suppression
        //récupérer l'Entitye manager
        $em = $doctrine->getManager();
        $em->remove($categories);
        //La maj au niveau de la bd
        $em->flush();
        return $this->redirectToRoute('app_categorie_blog');
    }


    #[Route('/updateCategorie/{id}', name: 'app_update_categorie')]
    public function updateCategorie($id, CategorieBlogRepository $rep, ManagerRegistry $doctrine, Request $request): Response
    {
        //récupérer la classe à modifier
        $categories = $rep->find($id);
        $form = $this->createForm( CategorieBlogType::class,$categories);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            //Action de MAJ
            $em =$doctrine->getManager();
            $em->flush();
            return $this->redirectToRoute("app_categorie_blog");
        }
        return $this->renderForm("categorie_blog/addCategorieBlog.html.twig", [
            "f" => $form
        ]);
    }




}
