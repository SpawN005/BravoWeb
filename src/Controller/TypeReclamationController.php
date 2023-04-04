<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\TypeReclamationRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Form\TypeReclamationFormType;
use App\Entity\Typereclamation;
use App\Entity\Reclamation;
use App\Repository\ReclamationRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;


#[Route('/typereclamation')]
class TypeReclamationController extends AbstractController
{
    #[Route('/type/reclamation', name: 'app_type_reclamation')]
    public function index(): Response
    {
        return $this->render('type_reclamation/index.html.twig', [
            'controller_name' => 'TypeReclamationController',
        ]);
    }

    #[Route('/readTypeReclamation', name: 'app_readTypeR')]
    public function readTypeReclamation(TypeReclamationRepository $r): Response
    {  
        //Utiliser findAll()
        $types=$r->findAll();
        return $this->render('type_reclamation/readTypeR.html.twig', [
            'types' => $types,
        ]);
    }

    #[Route('/addTypeReclamation', name: 'app_addTypeR')]
    public function addTypeReclamation(ManagerRegistry $doctrine,
    Request $request,ValidatorInterface $validator)
{
    $type = new TypeReclamation();
    $form = $this->createForm(TypeReclamationFormType::class, $type);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $em = $doctrine->getManager();
        
        // Récupérer l'ID de la dernière réclamation et l'utiliser pour l'ID du nouveau type de réclamation
        $lastReclamation = $em->getRepository(Reclamation::class)->findOneBy([], ['id' => 'desc']);
        $type->setId($lastReclamation->getId()+ 1);
        $errors = $validator->validate($type);
        if (count($errors) > 0) {
            $this->addFlash('error', 'Les données soumises ne sont pas valides');
            return $this->redirectToRoute('app_addTypeR');
        }
        // Action d'ajout
        $type->setTypereclamation($form->get('typereclamation')->getData());
        $em->persist($type);
        $em->flush();
        
        $this->addFlash('success', 'Le type de réclamation a bien été enregistré.');
        return $this->redirectToRoute("app_readTypeR");
    }

    return $this->renderForm("type_reclamation/addTypeR.html.twig", [
        "form" => $form
    ]);
                   }
     #[Route('/deleteTypeR/{id}', name: 'app_deleteTypeR')]
        public function deleteR($id , TypeReclamationRepository $rep, ManagerRegistry $doctrine): Response
             {
                //recuperer la classe a supprimer 
                $type=$rep->find($id);
                //action de suppression
                $em=$doctrine->getManager();
                $em->remove($type);
                $em->flush();// pour la mise a jour f bd 
                $this->addFlash('success', 'Le type de réclamation a bien été supprimé.');
                return $this->redirectToRoute('app_readTypeR', );
                              }

      #[Route('/updateTypeR/{id}', name: 'app_updateTypeR')]
         public function updateTypeR($id,TypeReclamationRepository $rep,
         ManagerRegistry $doctrine,Request $request)
         {
            //récupérer la classe à modifier
                $type=$rep->find($id);
                if (!$type) {
                    throw $this->createNotFoundException('Le type n\'existe pas.');
                }
                $form=$this->createForm(TypeReclamationFormType::class,$type);
                $form->handleRequest($request);
                if($form->isSubmitted()&& $form->isValid()){
                    //Action de Mise a jour 
                        $em =$doctrine->getManager() ;
                        $em->flush();
                        $this->addFlash('success', 'Le type de reclamation a bien été modifié.');
                        return $this->redirectToRoute("app_readTypeR");
                                     }
                return $this->renderForm("type_reclamation/updateTypeR.html.twig",
                                                    array("form"=>$form));
         }
          
          

        


}
