<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserEditType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\String\Slugger\SluggerInterface;

class ArtisteController extends AbstractController
{
    private $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;


    }


    #[Route('/front', name: 'front',methods: ['GET', 'POST'])]
    public function Front(): Response
    {
        return $this->render('frontTest.html.twig');
    }



    #[Route('/artiste', name: 'artiste',methods: ['GET', 'POST'])]
    public function Artiste(Security $security): Response
    {

        if ($security->getUser()) {
            if (in_array("ROLE_ARTISTE", $security->getUser()->getRoles())) {
                return $this->render('frontUser.html.twig', ['controller_name' => 'ArtisteController']);
            } else {
                return $this->redirectToRoute("app_login");

            }
        } else {
            return $this->redirectToRoute("app_login");
        }
    }

    #[Route('/artiste/profile/modifier', name: 'artisteProfile',methods: ['GET', 'POST'])]

    public function ArtisteProfile(ManagerRegistry $doctrine, Request $request, UserRepository $repository, SluggerInterface $slugger): response
    {
        $user= $this->getUser();
        $form=$this->createForm(UserEditType::class,$user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $photo = $form->get('image')->getData();

            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($photo) {
                $originalFilename = pathinfo($photo->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $photo->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $photo->move(
                        $this->getParameter('app.path.product_images'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $user->setImage($newFilename);
            }
            $em = $doctrine->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('artiste');
        }

        return $this->render('client/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    #[Route('/artiste/profile/modifier/{id}', name: 'deleteProfile')]

    public function DeleteUser(EntityManagerInterface $entityManager,User $user, UserRepository $repository,$id,ManagerRegistry $doctrine,Request $request ){

        $session = $request->getSession();

        $user = $repository->find($id);
        $entityManager =$doctrine->getManager();
        $entityManager->remove($user);
        $entityManager->flush();

        $session->remove($id);
        return $this->redirectToRoute('registration');

    }

}
