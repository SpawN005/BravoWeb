<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Blog;
use App\Entity\TwilioSmS;

use App\Entity\CategorieBlog;
use App\Entity\NoteBlog;
use App\Form\NoteBlogType;
use App\Form\BlogType;

use App\Form\SearchBlogFormType;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\BlogRepository;
use App\Repository\NoteBlogRepository;
use App\Repository\CategorieBlogRepository;

use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry as PersistenceManagerRegistry;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;


#[Route('/mobile')]
class BlogMobileController extends AbstractController
{
    #[Route('/', name: 'app_blog_mobile')]
    public function index(): Response
    {
        return $this->render('blog_mobile/index.html.twig', [
            'controller_name' => 'BlogMobileController',
        ]);
    }


    #[Route('/newJSON', name: 'app_new_blog_json')]
    public function addBlog( ManagerRegistry $doctrine, Request $request, UserRepository $rep,
     CategorieBlogRepository $cb, NormalizerInterface $normalizer)
    {
        // Récupérer les paramètres depuis la requête
        $title = $request->get('title');
        $description = $request->get('description');
        $content = $request->get('content');
        $categorie=$request->get('categorie');
        $image = 'images/blog-1-1.jpg';
    
        // Créer un nouveau blog avec les paramètres
        $blog = new Blog();
        $blog->setTitle($title);
        $blog->setDescription($description);
        $blog->setContent($content);
        $blog->setCategorie($cb->find(2));
        $blog->setImage($image);
        $blog->setAuthor($rep->findOneBy([]));
    
        //Action d'ajout
        $em = $this->getDoctrine()->getManager();
        $em->persist($blog);
        $em->flush();
    
        // Ajouter une propriété "image" à l'objet normalisé
        $jsonContent = $normalizer->normalize($blog, 'json', ['groups' => "blogs"]);
        $jsonContent['image'] = $image;
    
        // Convertir le tableau normalisé en chaîne JSON
        $responseContent = json_encode($jsonContent);
    
        // Renvoyer les données normalisées en JSON
        return new Response($responseContent);
    }


    #[Route('/updateBJSON/{id}', name: 'app_update_blog_json')]
    public function updateBlog($id, BlogRepository $rep, ManagerRegistry $doctrine, 
    Request $request,EntityManagerInterface $entityManager,
     NormalizerInterface $normalizer)
    {
        //récupérer la classe à modifier
        $em = $this->getDoctrine()->getManager();
        $blog= $em->getRepository(Blog::class)->find($id);

        //modifier les proprietes du blog
        $blog->setTitle($request->get('title'));
        $blog->setDescription($request->get('description'));
        $blog->setContent($request->get('content'));
        
        //faire la mise à jour dans la base de données
        $em->flush();
        $jsonContent = $normalizer->normalize($blog, 'json', ['groups' => "blogs"]);
        
        // Convertir le tableau normalisé en chaîne JSON
        $responseContent = json_encode($jsonContent);
    
        // Renvoyer les données normalisées en JSON
        return new Response($responseContent);
     
        }

    #[Route('/deleteBJSON/{id}', name: 'app_delete_blog_json')]
    public function deleteBlog($id, BlogRepository $rep, 
    ManagerRegistry $doctrine, NormalizerInterface $normalizer)
    {
        //récupérer la classe à supprimer
        $em = $this->getDoctrine()->getManager();
        $blog= $em->getRepository(Blog::class)->find($id);
        //Action de suppression
        $em->remove($blog);
        //La maj au niveau de la bd
        $em->flush();
        $jsonContent = $normalizer->normalize($blog, 'json',
         ['groups' => "blogs"]);

        // Convertir le tableau normalisé en chaîne JSON
        $responseContent = json_encode($jsonContent);
            
        // Renvoyer les données normalisées en JSON
        return new Response($responseContent);   
        }
       

    //affichage du blog pour les simples users
    #[Route('/readBlogJSON', name: 'app_read_blog_json')]
    public function readBlog(Request $request, BlogRepository $r, NormalizerInterface $normalizer): Response
    {
        //récupérer le repository 
        $r=$this->getDoctrine()->getRepository(Blog::class);
        $blogs=$r->findAll();

        $jsonContent = $normalizer->normalize($blogs, 'json', ['groups' => "blogs"]);
        return new Response(json_encode($jsonContent));
        
    }



}



