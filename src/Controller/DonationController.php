<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Donation;
use Symfony\Component\HttpFoundation\Request;
use App\Form\DonationType;
use Doctrine\Persistence\ManagerRegistry;



class DonationController extends AbstractController
{
    #[Route('/donation', name: 'app_donation', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {

        $donations = $entityManager
        ->getRepository(Donation::class)
        ->findAll();
        return $this->render('donation/index.html.twig', [
            'controller_name' => 'DonationController',
            'donation' => $donations
        ]);
    }
    #[Route('/donation/show', name: 'app_donation_show', methods:['GET'])]
    public function show(EntityManagerInterface $entityManager): Response
{
    $donations = $entityManager
        ->getRepository(Donation::class)
        ->findAll();
        return $this->render('donation/show.html.twig', [
            'controller_name' => 'DonationController',
            'donation' => $donations
        ]);
}
    #[Route('/donation/back', name: 'app_donation_back', methods:['GET'])]
    public function back(EntityManagerInterface $entityManager): Response
    {
        $donations = $entityManager
            ->getRepository(Donation::class)
            ->findAll();
        return $this->render('donation/back.html.twig', [
            'controller_name' => 'DonationController',
            'donation' => $donations
        ]);
    }

    #[Route('/donation/add', name: 'app_donation_add', methods: ['GET', 'POST'])]
     public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $donation = new Donation();
        $form = $this->createForm(DonationType::class, $donation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($donation);
            $entityManager->flush();

            return $this->redirectToRoute('app_donation', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('donation/add.html.twig', [
            'donation' => $donation,
            'form' => $form,
        ]);
    }
    #[Route('/updateDonation/{id}', name: 'app_donation_update')]
    public function updateDonation(ManagerRegistry $doctrine,$id,Request $req):Response{
        $em = $doctrine->getManager();
        $Donation = $doctrine->getRepository(Donation::class)->find($id);  
        $form = $this->createForm(DonationType::class,$Donation);

        $form->handleRequest($req);
        if ($form->isSubmitted()){
            $em->persist($Donation);
            $em->flush();
            return $this->redirectToRoute('app_donation');}
    return $this->renderForm('donation/add.html.twig',['form'=>$form]);
    }
    #[Route('/deleteDonation/{id}', name: 'app_donation_delete')]
    public function deleteDonation(ManagerRegistry $doctrine,$id): Response
    {
        $em = $doctrine->getManager();
        $donation = $doctrine->getRepository(Donation::class)->find($id);
        $em->remove($donation);
        $em->flush();
        return $this->redirectToRoute('app_donation');
    }



}
