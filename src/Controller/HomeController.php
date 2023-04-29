<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Artwork;
use App\Entity\Blog;
use Doctrine\ORM\EntityManagerInterface;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $artworks = $entityManager
            ->getRepository(Artwork::class)
            ->findAll();
        $blogs = $entityManager
            ->getRepository(Blog::class)
            ->findBy([], ['id' => 'DESC'], 3);;

        return $this->render('home/index.html.twig', [
            'artworks' => $artworks,
            'blogs' => $blogs,
        ]);
    }
}
