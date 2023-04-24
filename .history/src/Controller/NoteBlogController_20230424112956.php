<?php

namespace App\Controller;


use App\Entity\Blog;
use App\Entity\NoteBlog;
use App\Repository\UserRepository;
use App\Repository\BlogRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NoteBlogController extends AbstractController
{
    #[Route('/note/blog', name: 'app_note_blog')]
    public function index(): Response
    {
        return $this->render('note_blog/index.html.twig', [
            'controller_name' => 'NoteBlogController',
        ]);
    }


    #[Route('/new', name: 'app_new_note_blog', methods: ['POST'])]
       /* public function rate(Request $request, Blog $blog): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $score = $request->request->get('score');
        $existingNote = $entityManager->getRepository(NoteBlog::class)->findOneBy([
            'blogs' => $blog,
            'user' => $user
        ]);
        if ($existingNote) {
            // L'utilisateur a déjà voté, afficher une erreur
            $this->addFlash('error', 'Vous avez déjà voté pour ce blog.');
        } else {
            // Créer une nouvelle entité NoteBlog
            $note = new NoteBlog();
            $note->setScore($score);
            $note->setUser($user);
            $note->setBlog($blog);
            // Enregistrer la note dans la base de données
            $entityManager->persist($note);
            $entityManager->flush();
            $this->addFlash('success', 'Votre vote a été enregistré.');
            // Rediriger l'utilisateur vers la page du blog
            return $this->redirectToRoute('app_read_blog', ['blogs' => $blog]);
        }
    }*/

    public function rate(Request $request, Blog $blog): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $userId = 1; // Change this to the ID of the user you want to rate the blog
        $user = $entityManager->getRepository(User::class)->findById($userId);
        $score = $request->request->get('score');

        $existingNote = $entityManager->getRepository(NoteBlog::class)->findOneBy([
            'blog' => $blog,
            'user' => $user
    ]);

        if ($existingNote) {
            // L'utilisateur a déjà voté, afficher une erreur
            $this->addFlash('error', 'Vous avez déjà voté pour ce blog.');
        } else {
            // Créer une nouvelle entité NoteBlog
            $note = new NoteBlog();
            $note->setScore($score);
            $note->setUser($user);
            $note->setBlog($blog);

            // Enregistrer la note dans la base de données
            $entityManager->persist($note);
            $entityManager->flush();

            $this->addFlash('success', 'Votre vote a été enregistré.');

            // Rediriger l'utilisateur vers la page du blog
            return $this->redirectToRoute('app_detail_blog', ['blogs' => $blog]);
        }
        return $this->render('/blog/detailsBlog.html.twig',);
    }
}