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
use App\Repository\CommentsoeuvreRepository;
use App\Repository\NoteoeuvreRepository;
use Symfony\Component\Serializer\Normalizer\NormalizableInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Filesystem\Filesystem;



#[Route('/mobile')]
class MobileController extends AbstractController
{
    #[Route('/artwork')]
    public function index(EntityManagerInterface $entityManager, NormalizerInterface $normalizer): Response

    {
        $artworks = $entityManager
            ->getRepository(Artwork::class)
            ->findAll();

        $jsonContent = $normalizer->normalize($artworks, 'json');

        return new Response(json_encode($jsonContent));
    }
    #[Route('/artwork/categorie')]
    public function ShowArtCat(EntityManagerInterface $entityManager, NormalizerInterface $normalizer): Response

    {
        $cat = $entityManager
            ->getRepository(Categorie::class)
            ->findAll();

        $jsonContent = $normalizer->normalize($cat, 'json');

        return new Response(json_encode($jsonContent));
    }
    #[Route('/artwork/new')]
    public function newArt(EntityManagerInterface $entityManager, Request $request, NormalizerInterface $normalizer): Response

    {

        $filesystem = new Filesystem();
        $filesystem->copy($request->get('url'), "C:/xampp/htdocs/img/" . $request->get("title") . ".png");


        $artwork = new Artwork();
        $artwork->setTitle($request->get("title"));
        $artwork->setDescription($request->get("description"));
        $artwork->setUrl($request->get("title") . ".png");
        $categorieName = $request->get("categorie");
        $id = $request->get("user");

        $user = $entityManager->getRepository(User::class)->find($id);
        $categorie = $entityManager->getRepository(Categorie::class)->findOneBynomcategorie($categorieName);
        $artwork->setOwner($user);
        $artwork->setCategorie($categorie);

        $entityManager->persist($artwork);
        $entityManager->flush();
        $jsonContent = $normalizer->normalize($artwork, 'json');

        return new Response("Art added Successfully " . json_encode($jsonContent));
    }
    #[Route('/artwork/update/{id}')]
    public function updateArt(EntityManagerInterface $entityManager, $id, Request $request, NormalizerInterface $normalizer): Response

    {
        $artwork =  $entityManager->getRepository(Artwork::class)->find($id);
        $categorieName = $request->get("categorie");
        $categorie = $entityManager->getRepository(Categorie::class)->findOneBynomcategorie($categorieName);
        $artwork->setCategorie($categorie);
        $artwork->setUrl($request->get("url"));
        $artwork->setDescription($request->get("description"));
        $artwork->setTitle($request->get("title"));


        $entityManager->flush();
        $jsonContent = $normalizer->normalize($artwork, 'json');

        return new Response("Art Updated Successfully " . json_encode($jsonContent));
    }
    #[Route('/artwork/delete/{id}')]
    public function deleteArt(EntityManagerInterface $entityManager, $id, Request $request, NormalizerInterface $normalizer): Response

    {
        $artwork =  $entityManager->getRepository(Artwork::class)->find($id);
        $entityManager->remove($artwork);
        $entityManager->flush();


        return new Response("Art Updated Removed ");
    }
    #[Route('/artwork/{id}')]
    public function Art(EntityManagerInterface $entityManager, Request $request, $id, NormalizerInterface $normalizer): Response

    {
        $artwork = $entityManager
            ->getRepository(Artwork::class)
            ->find($id);
        $jsonContent = $normalizer->normalize($artwork, 'json');
        return new Response(json_encode($jsonContent));
    }
    #[Route('/comments/artwork')]
    public function ArtComments(EntityManagerInterface $entityManager, Request $request, NormalizerInterface $normalizer): Response
    {

        $artwork =  $entityManager->getRepository(Artwork::class)->find($request->get("art"));

        $comments =  $entityManager->getRepository(Commentsoeuvre::class)->findByOeuvre($artwork);
        $jsonContent = $normalizer->normalize($comments, 'json');
        return new Response(json_encode($jsonContent));
    }
    #[Route('/comment/artwork/new')]
    public function NewArtComment(EntityManagerInterface $entityManager, Request $request, NormalizerInterface $normalizer): Response
    {

        $artwork =  $entityManager->getRepository(Artwork::class)->find($request->get("art"));
        $comments = new Commentsoeuvre();
        $comments->setComment($request->get("comment"));
        $comments->setOeuvre($artwork);

        $entityManager->persist($comments);
        $entityManager->flush();
        $jsonContent = $normalizer->normalize($comments, 'json');

        return new Response("Comment added Successfully " . json_encode($jsonContent));
    }
    #[Route('/note/artwork')]
    public function noteArt(EntityManagerInterface $entityManager, Request $request, NormalizerInterface $normalizer): Response
    {
        $artwork =  $entityManager->getRepository(Artwork::class)->find($request->get("art"));

        $notes = $entityManager
            ->getRepository(Noteoeuvre::class)
            ->findByidOeuvre($artwork);
        $total = 0;
        $count = count($notes);

        foreach ($notes as $note) {
            $total += $note->getNote();
        }

        $average = $count > 0 ? $total / $count : 0;

        $date[] = [
            "note" => $average,
        ];
        $jsonContent = $normalizer->normalize($date, 'json');

        return new Response(json_encode($jsonContent));
    }
    #[Route('/vote/artwork')]
    public function Vote(EntityManagerInterface $entityManager, Request $request, NormalizerInterface $normalizer): Response
    {
        $artwork = $entityManager
            ->getRepository(Artwork::class)
            ->findOneByid($request->get('art'));
        $user = $entityManager
            ->getRepository(User::class)
            ->findOneByid($request->get('user'));
        $vote = $entityManager->getRepository(Noteoeuvre::class)->findOneBy([
            'idOeuvre' => $artwork,
            'idUser' => $user
        ]);
        if (!$vote) {
            $vote = new Noteoeuvre();
            $vote->setIdOeuvre($artwork);
            $vote->setIdUser($user);
        }
        $vote->setNote($request->get('note'));
        $entityManager->persist($vote);
        $entityManager->flush();
        $jsonContent = $normalizer->normalize($vote, 'json');

        return new Response(json_encode($jsonContent));
    }
}
