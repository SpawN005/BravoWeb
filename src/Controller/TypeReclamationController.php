<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\TypeReclamationRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\TypeReclamationFormType;
use App\Entity\TypeReclamation;
use App\Entity\Reclamation;
use App\Repository\ReclamationRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;



class TypeReclamationController extends AbstractController
{


    #[Route('/admin/readTypeReclamation', name: 'app_readTypeR')]
    public function readTypeReclamation(TypeReclamationRepository $r): Response
    {
        //Utiliser findAll()
        $types = $r->findAll();
        return $this->render('type_reclamation/readTypeR.html.twig', [
            'types' => $types,
        ]);
    }

    #[Route('/admin/addTypeReclamation', name: 'app_addTypeR')]
    public function addTypeReclamation(
        ManagerRegistry $doctrine,
        Request $request,
        ValidatorInterface $validator
    ) {
        $type = new TypeReclamation();
        $form = $this->createForm(TypeReclamationFormType::class, $type);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();

            // Récupérer l'ID de la dernière réclamation et l'utiliser pour l'ID du nouveau type de réclamation

            $errors = $validator->validate($type);
            if (count($errors) > 0) {
                $this->addFlash('error', 'Les données soumises ne sont pas valides');
                return $this->redirectToRoute('app_addTypeR');
            }
            // Action d'ajout
            $type->setTypeReclamation($form->get('TypeReclamation')->getData());
            $em->persist($type);
            $em->flush();

            $this->addFlash('success', 'Le type de réclamation a bien été enregistré.');
            return $this->redirectToRoute("app_readTypeR");
        }

        return $this->renderForm("type_reclamation/addTypeR.html.twig", [
            "form" => $form
        ]);
    }
    #[Route('/admin/deleteTypeR/{id}', name: 'app_deleteTypeR')]
    public function deleteR($id, TypeReclamationRepository $rep, ManagerRegistry $doctrine): Response
    {
        //recuperer la classe a supprimer 
        $type = $rep->find($id);
        //action de suppression
        $em = $doctrine->getManager();
        $em->remove($type);
        $em->flush(); // pour la mise a jour f bd 
        $this->addFlash('success', 'Le type de réclamation a bien été supprimé.');
        return $this->redirectToRoute('app_readTypeR',);
    }

    #[Route('/admin/updateTypeR/{id}', name: 'app_updateTypeR')]
    public function updateTypeR(
        $id,
        TypeReclamationRepository $rep,
        ManagerRegistry $doctrine,
        Request $request
    ) {
        //récupérer la classe à modifier
        $type = $rep->find($id);
        if (!$type) {
            throw $this->createNotFoundException('Le type n\'existe pas.');
        }
        $form = $this->createForm(TypeReclamationFormType::class, $type);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            //Action de Mise a jour 
            $em = $doctrine->getManager();
            $em->flush();
            $this->addFlash('success', 'Le type de reclamation a bien été modifié.');
            return $this->redirectToRoute("app_readTypeR");
        }
        return $this->renderForm(
            "type_reclamation/updateTypeR.html.twig",
            array("form" => $form)
        );
    }

    #[Route('/admin/deleteTypecheck/{id}', name: 'app_deleteTypeCheck')]
    public function delete($id, TypeReclamationRepository $rep, Request $request, TypeReclamation $TypeReclamation, EntityManagerInterface $entityManager): Response
    { //recuperer le type a supprimer 
        $type = $rep->find($id);
        // Vérifier s'il existe des réclamations associées au type de réclamation
        $reclamations = $this->getDoctrine()->getRepository(Reclamation::class)->findBy(['TypeReclamation' => $type]);
        if (count($reclamations) > 0) {
            // Si oui, demander confirmation avant la suppression
            $alertMessage = "Attention ! Ce type de réclamation est déjà associé à des réclamations. Êtes-vous sûr de vouloir le supprimer ?";
            $alertForm = $this->createFormBuilder()
                ->setAction($this->generateUrl('app_deleteTypeCheck', ['id' => $TypeReclamation->getId()]))
                ->add('confirm', SubmitType::class, ['label' => 'Confirmer la suppression'])
                ->getForm();
            $alertForm->handleRequest($request);
            if ($alertForm->isSubmitted() && $alertForm->isValid()) {
                // Si le formulaire de confirmation est soumis et valide, mettre à jour les réclamations associées
                // au type de réclamation en les affectant au type "Autre"
                foreach ($reclamations as $reclamation) {
                    $reclamation->setTypeReclamation($entityManager->getRepository(TypeReclamation::class)->findOneBy(['TypeReclamation' => 'Autre']));
                    $entityManager->persist($reclamation);
                }
                // Supprimer le type de réclamation
                $entityManager->remove($TypeReclamation);
                $entityManager->flush();

                $this->addFlash('success', 'Le type de réclamation a été supprimé avec succès.');
                return $this->redirectToRoute('app_readTypeR');
            } else {
                // Si le formulaire n'est pas encore soumis, retourner la vue avec le formulaire
                return $this->render('type_reclamation/delete_confirm.html.twig', [
                    'TypeReclamation' => $TypeReclamation,
                    'alertMessage' => $alertMessage,
                    'alertForm' => $alertForm->createView(),
                ]);
            }
        } else {
            // Sinon, supprimer directement le type de réclamation
            $entityManager->remove($TypeReclamation);
            $entityManager->flush();
            $this->addFlash('success', 'Le type de réclamation a été supprimé avec succès.');
            return $this->redirectToRoute('app_readTypeR');
        }
    }
}
