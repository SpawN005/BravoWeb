<?php

namespace App\Controller;



use App\Entity\Artwork;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'app_dashboard')]
    public function index(): Response
    {
        return $this->render('dashboard/index.html.twig', [
            'controller_name' => 'DashboardController',
        ]);
    }

    #[Route('/dashboard/artwork', name: 'dashboard_artwork_index', methods: ['GET'])]
    public function indexDashboard(EntityManagerInterface $entityManager): Response
    {
        $artworks = $entityManager
            ->getRepository(Artwork::class)
            ->findAll();
        return $this->render('dashboard/artwork-index.html.twig', [
            'artworks' => $artworks,
        ]);
    }
}
