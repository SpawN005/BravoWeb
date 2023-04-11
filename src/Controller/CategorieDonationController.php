<?php

namespace App\Controller;

use App\Entity\CategorieDonation;
use App\Form\CategorieDonationType;
use App\Repository\CategorieDonationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/categorie/donation')]
class CategorieDonationController extends AbstractController
{
    #[Route('/', name: 'app_categorie_donation_index', methods: ['GET'])]
    public function index(CategorieDonationRepository $categorieDonationRepository): Response
    {
        return $this->render('categorie_donation/index.html.twig', [
            'categorie_donations' => $categorieDonationRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_categorie_donation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CategorieDonationRepository $categorieDonationRepository): Response
    {
        $categorieDonation = new CategorieDonation();
        $form = $this->createForm(CategorieDonationType::class, $categorieDonation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $categorieDonationRepository->save($categorieDonation, true);

            return $this->redirectToRoute('app_categorie_donation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('categorie_donation/new.html.twig', [
            'categorie_donation' => $categorieDonation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_categorie_donation_show', methods: ['GET'])]
    public function show(CategorieDonation $categorieDonation): Response
    {
        return $this->render('categorie_donation/show.html.twig', [
            'categorie_donation' => $categorieDonation,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_categorie_donation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, CategorieDonation $categorieDonation, CategorieDonationRepository $categorieDonationRepository): Response
    {
        $form = $this->createForm(CategorieDonationType::class, $categorieDonation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $categorieDonationRepository->save($categorieDonation, true);

            return $this->redirectToRoute('app_categorie_donation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('categorie_donation/edit.html.twig', [
            'categorie_donation' => $categorieDonation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_categorie_donation_delete', methods: ['POST'])]
    public function delete(Request $request, CategorieDonation $categorieDonation, CategorieDonationRepository $categorieDonationRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$categorieDonation->getId(), $request->request->get('_token'))) {
            $categorieDonationRepository->remove($categorieDonation, true);
        }

        return $this->redirectToRoute('app_categorie_donation_index', [], Response::HTTP_SEE_OTHER);
    }
}
