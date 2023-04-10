<?php

namespace App\Controller;

use App\Entity\Donater;
use App\Form\DonaterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/donater/conntroller')]
class DonaterConntrollerController extends AbstractController
{
    #[Route('/', name: 'app_donater_conntroller_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $donaters = $entityManager
            ->getRepository(Donater::class)
            ->findAll();

        return $this->render('donater_conntroller/index.html.twig', [
            'donaters' => $donaters,
        ]);
    }

    #[Route('/new', name: 'app_donater_conntroller_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $donater = new Donater();
        $form = $this->createForm(DonaterType::class, $donater);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($donater);
            $entityManager->flush();

            return $this->redirectToRoute('app_donater_conntroller_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('donater_conntroller/new.html.twig', [
            'donater' => $donater,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_donater_conntroller_show', methods: ['GET'])]
    public function show(Donater $donater): Response
    {
        return $this->render('donater_conntroller/show.html.twig', [
            'donater' => $donater,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_donater_conntroller_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Donater $donater, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(DonaterType::class, $donater);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_donater_conntroller_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('donater_conntroller/edit.html.twig', [
            'donater' => $donater,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_donater_conntroller_delete', methods: ['POST'])]
    public function delete(Request $request, Donater $donater, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$donater->getId(), $request->request->get('_token'))) {
            $entityManager->remove($donater);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_donater_conntroller_index', [], Response::HTTP_SEE_OTHER);
    }
}
