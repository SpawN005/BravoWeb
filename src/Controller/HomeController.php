<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Artwork;
use App\Entity\Blog;
use App\Entity\Event;
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
            ->findBy([], ['id' => 'DESC'], 3);
        $latestevent = $entityManager
            ->getRepository(Event::class)
            ->findOneBy([], ['date_beg' => 'ASC']);
        $randomEvents = $entityManager
            ->getRepository(Event::class)
            ->findBy([], ['id' => 'DESC'], 3);
        $upcomingEvents = $entityManager
            ->getRepository(Event::class)
            ->findBy([], ['date_beg' => 'ASC'], 3);

        return $this->render('home/index.html.twig', [
            'artworks' => $artworks,
            'blogs' => $blogs,
            'latestevent' => $latestevent,
            'randomEvents' => $randomEvents,
            'upcomingEvents' => $upcomingEvents,
        ]);
    }
}
