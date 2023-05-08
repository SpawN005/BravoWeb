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
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class CategorieBlogMobileController extends AbstractController
{
    #[Route('/readCBJSON', name: 'app_categorie_blog_mobile')]
    public function index(EntityManagerInterface $entityManager, NormalizerInterface $normalizer): Response
    {
        //récupérer le repository 
        $r=$this->getDoctrine()->getRepository(CategorieBlog::class);
        $categorie=$r->findAll();

        $jsonContent = $normalizer->normalize($categorie, 'json', ['groups' => "categories"]);
        return new Response(json_encode($jsonContent));
    }
}
