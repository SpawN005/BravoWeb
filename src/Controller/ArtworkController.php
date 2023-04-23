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
use App\Repository\NoteoeuvreRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Translation\TranslatableMessage;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;



use Symfony\Contracts\HttpClient\HttpClientInterface;




#[Route('/artwork')]
class ArtworkController extends AbstractController
{
    #[Route('/', name: 'app_artwork_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager, PaginatorInterface $paginator, Request $request): Response

    {

        $artworks = $entityManager
            ->getRepository(Artwork::class)
            ->findAll();
        $categories = $entityManager
            ->getRepository(Categorie::class)
            ->findAll();
        $pagination = $paginator->paginate(
            $artworks,
            $request->query->getInt('page', 1),
            6,

        );
        $pagination->setCustomParameters([
            'align' => 'center',
            'size' => 'large',
            'rounded' => true,
        ]);

        return $this->render('artwork/index.html.twig', [

            "categories" => $categories,
            "pagination" => $pagination,
        ]);
    }

    #[Route('/new', name: 'app_artwork_new', methods: ['GET', 'POST'])]
    public function new(HttpClientInterface $httpClient, Request $request, EntityManagerInterface $entityManager, FlashyNotifier $flashy): Response
    {

        $artwork = new Artwork();
        $form = $this->createForm(ArtworkType::class, $artwork);
        $form->handleRequest($request);
        $artworks = $entityManager
            ->getRepository(Artwork::class)
            ->findByTitle($form['title']->getData());

        if ($artworks) {
            $flashy->info("Title taken");
        } else {
            if ($form->isSubmitted() && $form->isValid()) {






                $translate = $httpClient->request('POST', 'https://rapid-translate-multi-traduction.p.rapidapi.com/t', [
                    'headers' => [
                        'content-type' => 'application/json',
                        'X-RapidAPI-Key' => '5663b0b24emsh9f1230312127163p13953ajsnc45c9ef48937',
                        'X-RapidAPI-Host' => 'rapid-translate-multi-traduction.p.rapidapi.com',
                    ],
                    'body' => json_encode([
                        'from' => 'fr',
                        'to' =>  'en',
                        'q' => $form['description']->getData()

                    ]),
                ]);

                $response = $httpClient->request('POST', 'https://profanity-cleaner-bad-word-filter.p.rapidapi.com/profanity', [
                    'headers' => [
                        'content-type' => 'application/json',
                        'X-RapidAPI-Key' => '5663b0b24emsh9f1230312127163p13953ajsnc45c9ef48937',
                        'X-RapidAPI-Host' => 'profanity-cleaner-bad-word-filter.p.rapidapi.com',
                    ],
                    'body' => json_encode([
                        'text' => $translate->toArray()[0],
                        'maskCharacter' =>  'x',
                        'language' => 'en'
                    ]),
                ]);
                if ($response->toArray()["profanities"]) {
                    $flashy->error('Bad Word detected!', 'http://your-awesome-link.com');
                } else {
                    $file = $form['url']->getData();
                    $fileName = $file->getClientOriginalName();
                    $file->move("C:/xampp/htdocs/img", $fileName);
                    $artwork->setUrl($fileName);
                    $entityManager->persist($artwork);
                    $entityManager->flush();
                    $flashy->info('Artwork Created!', 'http://your-awesome-link.com');

                    return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
                }
            }
        }

        return $this->renderForm('artwork/new.html.twig', [
            'artwork' => $artwork,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_artwork_show', methods: ['GET', 'POST'])]
    public function show(Request $request, CommentsoeuvreRepository $commentsoeuvreRepository, Artwork $artwork, EntityManagerInterface $entityManager): Response
    {
        $notes = $entityManager
            ->getRepository(Noteoeuvre::class)
            ->findByidOeuvre($artwork);
        $total = 0;
        $count = count($notes);

        foreach ($notes as $note) {
            $total += $note->getNote();
        }

        $average = $count > 0 ? $total / $count : 0;
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
            return $this->redirectToRoute('app_artwork_show', ['id' => $artwork->getId()]);
        }
        if ($note->isSubmitted()) {
            $vote = $entityManager->getRepository(Noteoeuvre::class)->findOneBy([
                'idOeuvre' => $artwork,
                'idUser' => $user
            ]);
            if (!$vote) {
                $vote = new Noteoeuvre();
                $vote->setIdOeuvre($artwork);
                $vote->setIdUser($user);
            }
            $vote->setNote($note['note']->getData());
            $entityManager->persist($vote);
            $entityManager->flush();
            return $this->redirectToRoute('app_artwork_show', ['id' => $artwork->getId()]);
        }
        return $this->render('artwork/show.html.twig', [
            'artwork' => $artwork,
            'comments' => $comments,
            'form' => $form->createView(),
            'note' => $note->createView(),
            'notes' => $average
        ]);
    }

    #[Route('/{id}/edit', name: 'app_artwork_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Artwork $artwork, EntityManagerInterface $entityManager, FlashyNotifier $flashy): Response
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
            $flashy->success('Artwork Modified!', 'http://your-awesome-link.com');
            $entityManager->flush();
            return $this->redirectToRoute('app_artwork_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('artwork/edit.html.twig', [
            'artwork' => $artwork,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/delete', name: 'app_artwork_delete', methods: ['POST'])]
    public function delete(NoteoeuvreRepository $noteoeuvreRepository, CommentsoeuvreRepository $commentsoeuvreRepository, Request $request, Artwork $artwork, EntityManagerInterface $entityManager, FlashyNotifier $flashy): Response
    {



        if ($this->isCsrfTokenValid('delete' . $artwork->getId(), $request->request->get('_token'))) {
            $commentsoeuvreRepository->removeByOeuvreId($artwork->getId());
            $noteoeuvreRepository->removeByOeuvreId($artwork->getId());
            $entityManager->remove($artwork);
            $entityManager->flush();
        }
        $flashy->success('Artwork deleted!', 'http://your-awesome-link.com');
        return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('/{id}/comment/delete', name: 'app_comment_delete', methods: ['GET', 'DELETE'])]
    public function deleteComment(Commentsoeuvre $commentsoeuvre, EntityManagerInterface $entityManager): Response
    {

        $artwork = new Artwork();
        $artwork = $entityManager
            ->getRepository(Artwork::class)
            ->findOneById($commentsoeuvre->getOeuvre());
        $entityManager->remove($commentsoeuvre);
        $entityManager->flush();

        return $this->redirectToRoute('app_artwork_show', ['id' => $artwork->getId()]);
    }
}
