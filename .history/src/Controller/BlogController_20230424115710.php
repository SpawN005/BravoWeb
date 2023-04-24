<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Blog;
use App\Entity\CategorieBlog;
use App\Entity\NoteBlog;
use App\Form\NoteBlogType;
use App\Form\BlogType;

use App\Form\SearchBlogFormType;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\BlogRepository;
use App\Repository\NoteBlogRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry as PersistenceManagerRegistry;



#[Route('/blog')]
class BlogController extends AbstractController
{

    //affichage du blog pour l'artiste ( ses blogs) 
    #[Route('/', name: 'app_blog')]
    public function index(): Response
    {
        //récupérer le repository 
        $r=$this->getDoctrine()->getRepository(Blog::class);
        //Utiliser findAll()
        $blogs=$r->findAll();
        return $this->render('blog/index.html.twig', [
            'controller_name' => 'BlogController',
            'blog' => $blogs,
        ]);
    }


    #[Route('/new', name: 'app_new_blog')]
    public function addBlog(ManagerRegistry $doctrine,
    Request $request, UserRepository $rep)
    {
        $blog= new Blog();
        $form=$this->createForm(BlogType::class,$blog);
        $form->handleRequest($request);
        if($form->isSubmitted()  && $form->isValid()){

            $file = $form['image']->getData();
            $fileName = $file->getClientOriginalName();
            $file->move("C:/xampp/htdocs/img", $fileName);
            $blog->setImage($fileName);
            
            //recuperer le user connecté:
            //$blog->setAuthor($this->security->getUser());  
            //$blog->setAuthor();  
            
            //Action d'ajout
            $em =$doctrine->getManager() ;
            $em->persist($blog);
            $em->flush();
            return $this->redirectToRoute("app_blog");
        }
    return $this->renderForm("blog/addBlog.html.twig",array("f"=>$form));
}


    #[Route('/deleteB/{id}', name: 'app_delete_blog')]
    public function deleteBlog($id, BlogRepository $rep, 
    ManagerRegistry $doctrine): Response
{
        //récupérer la classe à supprimer
        $blog=$rep->find($id);
        //Action de suppression
        //récupérer l'Entitye manager
        $em=$doctrine->getManager();
        $em->remove($blog);
        //La maj au niveau de la bd
        $em->flush();
        return $this->redirectToRoute('app_blog');
}


    #[Route('/updateB/{id}', name: 'app_update_blog')]
    public function updateBlog($id, BlogRepository $rep, ManagerRegistry $doctrine, 
    Request $request,EntityManagerInterface $entityManager)
    {
        //récupérer la classe à modifier
        $blog = $rep->find($id);
        $form = $this->createForm(BlogType::class,$blog);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form['image']->getData();
            $fileName = $file->getClientOriginalName();
            $file->move("C:/xampp/htdocs/img", $fileName);
            $blog->setImage($fileName);
            $entityManager->flush();
            return $this->redirectToRoute("app_blog");
        }
    return $this->renderForm("blog/addBlog.html.twig", array("f"=>$form));
    }   
    
    //details du blogs pour les simples users
    #[Route('/detailBlog/{id}', name: 'app_detail_blog', methods: ["GET", "POST"] )]
    public function show($id, BlogRepository $rep, Request $request, NoteBlogRepository $nb): Response
    {
        //Utiliser find by id
        $blogs = $rep->find($id);
        $blogId = $blogs->getId();
        $note = $nb->countByNote($id);
        $noteblog= new NoteBlog();
        $form = $this->createForm(NoteBlogType::class, $noteblog );
        $form->handleRequest($request);
        

        if ($form->isSubmitted() && $form->isValid()) {


        $entityManager = $this->getDoctrine()->getManager();
        $userId = 1; // Change this to the ID of the user you want to rate the blog
        $user = $entityManager->getRepository(User::class)->find($userId);
        // $score = $request->request->get('note');
        $score =$form->get('note')->getData();

        $existingNote = $entityManager->getRepository(NoteBlog::class)->findOneBy([
            'blog' => $blogs,
            'user' => $user
    ]);

        if ($existingNote) {
            // L'utilisateur a déjà voté, afficher une erreur
            $this->addFlash('error', 'Vous avez déjà voté pour ce blog.');
        } else {
            // Créer une nouvelle entité NoteBlog
            $note = new NoteBlog();
            $note->setNote($score);
            $note->setUser($user);
            $note->setBlog($blogs);

            // Enregistrer la note dans la base de données
            $entityManager->persist($note);
            $entityManager->flush();

            $this->addFlash('success', 'Votre vote a été enregistré.');

            // Rediriger l'utilisateur vers la page du blog
            return $this->redirectToRoute('app_detail_blog', ['blogs' => $blogs]);
        }
    }
    
        return $this->render('blog/detailsBlog.html.twig', [
            'blog' => $blogs,
            'blogId' => $blogId,
            'note' =>$note,
            'form' => $form -> createView()
            

        ]);
    }

    //affichage du blog pour les simples users
    #[Route('/readBlog', name: 'app_read_blog')]
    public function readBlog(Request $request, BlogRepository $r): Response
    {
        //récupérer le repository 
        $r=$this->getDoctrine()->getRepository(Blog::class);
        $blogs=$r->findAll();
        
            return $this->render('blog/readBlog.html.twig', [
            'blog' => $blogs,        
        ]);
    }

    //la méthode de recherche
#[Route('/searchBlogByTitle', name: 'app_search_blog')]  
    public function searchBlog(Request $r, BlogRepository $rep)
    {
        $title = null;
        $categorie = null;
        $blogs = [];
        $form = $this->createForm(SearchBlogFormType::class);
        $form->handleRequest($r);
        if ($form->isSubmitted() && $form->isValid()) {
            $title = $form->get('title')->getData();
            $categorie = $form->get('categorie')->getData();

            $resultOfSearch = $rep->findByTitleAndCategorie($title,$categorie);

            return $this->renderForm('blog/search.html.twig', [
                'blog' => $resultOfSearch,
                'searchBlogByTitle' => $form,

            ]);
        } 
        $blogs = $rep->findAll();     
        
        return $this->renderForm('blog/search.html.twig', ['blog' => $blogs , 
                        'searchBlogByTitle' => $form,
                        'title' =>$title,
                        'categorie' => $categorie,
                        ]);    
     
    }
    
}
