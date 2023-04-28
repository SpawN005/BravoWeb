<?php

// src/Controller/YourController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\String\Slugger\SluggerInterface;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\UserRepository;
use App\Form\UserEditType;
use App\Entity\User;
use Symfony\Component\Routing\Annotation\Route;

class YourController extends AbstractController
{
    /**
     * @Route("/save-image", name="save_image", methods={"POST"})
     */
    public function saveImage(Request $request): JsonResponse
    {
        // Get the image data from the request body
        $data = json_decode($request->getContent(), true);
        $imageData = $data['image'];

        // Save the image to the user entity
        $user = $this->getUser();
        $filename = 'user-' . $user->getId() . '-' . uniqid() . '.png'; // Generate a unique filename for the image
        $filePath = $this->getParameter('app.path.product_images') . '/' . $filename;
        $imageData = str_replace('data:image/png;base64,', '', $imageData);
        $imageData = str_replace(' ', '+', $imageData);
        $imageBinary = base64_decode($imageData);
        file_put_contents($filePath, $imageBinary);
        $user->setImage($filename);
        // Save the updated user entity to the database
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($user);
        $entityManager->flush();
        return $this->json(['success' => true]);
    }
}
