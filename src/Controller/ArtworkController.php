<?php

namespace App\Controller;

use App\Entity\Artwork;
use App\Entity\Commentsoeuvre;
use App\Entity\Categorie;
use App\Entity\Noteoeuvre;
use App\Entity\User;
use App\Form\ArtworkType;
use App\Form\CommentoeuvreType;
use App\Form\NoteOeuvreType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Filesystem\Filesystem;
use App\Repository\CommentsoeuvreRepository;

#[Route('/artwork')]
class ArtworkController extends AbstractController
{
    #[Route('/', name: 'app_artwork_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $artworks = $entityManager
            ->getRepository(Artwork::class)
            ->findAll();
        $categories = $entityManager
            ->getRepository(Categorie::class)
            ->findAll();


        return $this->render('artwork/index.html.twig', [
            'artworks' => $artworks,
            "categories" => $categories,
        ]);
    }

    #[Route('/new', name: 'app_artwork_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $artwork = new Artwork();
        $form = $this->createForm(ArtworkType::class, $artwork);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form['url']->getData();
            $fileName = $file->getClientOriginalName();
            $file->move("C:/xampp/htdocs/img", $fileName);
            $artwork->setUrl($fileName);
            $entityManager->persist($artwork);
            $entityManager->flush();
            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('artwork/new.html.twig', [
            'artwork' => $artwork,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_artwork_show', methods: ['GET', 'POST'])]
    public function show(Request $request, CommentsoeuvreRepository $commentsoeuvreRepository, Artwork $artwork, EntityManagerInterface $entityManager): Response
    {
        $user = $entityManager
            ->getRepository(User::class)
            ->findOneById(1);

        $comments = $commentsoeuvreRepository->findByOeuvreId($artwork->getId());

        $form = $this->createForm(CommentoeuvreType::class, new Commentsoeuvre());
        $form->handleRequest($request);
        $note = $this->createForm(NoteOeuvreType::class, new Noteoeuvre());
        $note->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $comment = new Commentsoeuvre();
            $comment->setComment($form['comment']->getData());
            $comment->setOeuvre($artwork);
            $comment->setUser($user);
            $entityManager->persist($comment);
            $entityManager->flush();
            dump($comment);

            return $this->redirectToRoute('app_artwork_show', ['id' => $artwork->getId()]);
        }
        return $this->render('artwork/show.html.twig', [
            'artwork' => $artwork,
            'comments' => $comments,
            'form' => $form->createView(),
            'note' => $note->createView()
        ]);
    }

    #[Route('/{id}/edit', name: 'app_artwork_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Artwork $artwork, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ArtworkType::class, $artwork);
        $form->handleRequest($request);
        $oldImage = $artwork->getUrl();

        if ($form->isSubmitted() && $form->isValid()) {
            $newImage = $form['url']->getData();

            if ($newImage) {
                $newImage->move("C:/xampp/htdocs/img", $newImage->getClientOriginalName());
                $artwork->setUrl($newImage->getClientOriginalName());
            }

            $entityManager->flush();
            return $this->redirectToRoute('app_artwork_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('artwork/edit.html.twig', [
            'artwork' => $artwork,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_artwork_delete', methods: ['POST'])]
    public function delete(Request $request, Artwork $artwork, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $artwork->getId(), $request->request->get('_token'))) {
            $entityManager->remove($artwork);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
    }
}
