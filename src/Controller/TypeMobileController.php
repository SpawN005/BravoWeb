<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use App\Repository\TypeReclamationRepository;


class TypeMobileController extends AbstractController
{
    #[Route('/type/mobile', name: 'app_type_mobile')]
    public function index(): Response
    {
        return $this->render('type_mobile/index.html.twig', [
            'controller_name' => 'TypeMobileController',
        ]);
    }

    #[Route('/type-json', name: 'app_type_json')]
    public function getReclamationsJson(TypeReclamationRepository $r, NormalizerInterface $Normalizer): Response
    {
        $types = $r->findAll();
        $jsonContent = $Normalizer->normalize($types, 'json',['Groups'=>"types"]);
    
        return new Response(json_encode($jsonContent));
    }
   
}
