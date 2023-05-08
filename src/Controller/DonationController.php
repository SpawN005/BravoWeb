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
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;





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
    public function show(Request $request, EntityManagerInterface $entityManager): Response
    {
        $searchTerm = $request->query->get('search');
        $donations = $entityManager->getRepository(Donation::class)->findAll();
        if ($searchTerm) {
            $donations = $entityManager->getRepository(Donation::class)->createQueryBuilder('d')
                ->where('d.title LIKE :title')
                ->setParameter('title', '%' . $searchTerm . '%')
                ->getQuery()
                ->getResult();
        }
        return $this->render('donation/show.html.twig', [
            'controller_name' => 'DonationController',
            'donation' => $donations,
            'searchTerm' => $searchTerm
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

///////////////////////////////// json lenna
    #[Route('/getall', name: 'getall')]
    public function stationb(NormalizerInterface $serializer): Response
    {
        $r=$this->getDoctrine()->getRepository(Donation::class);
        $messtation = $r->findAll();
        $snorm=$serializer->normalize($messtation,'json',['groups'=>'stations']);
        $json= json_encode($snorm);
        return new Response($json);
    }
/////////////////////////////////////json

    #[Route('/add', name: 'add')]
    public function addstation(Request $request,
                               NormalizerInterface $Normalizer): Response
    {
        $em = $this->getDoctrine()->getManager();
        $station = new station();
        $station->setNomStation($request->get('nomStation'));
        $station->setLocalisationStation($request->get('localisationStation'));
        $station->setVeloStation($request->get('veloStation'));
        $em->persist($station);
        $em->flush();

        $jsonContent = $Normalizer->normalize($station, 'js
on', ['groups' => 'stations']);
        return new Response(json_encode($jsonContent));
    }
/////////////////////////////////////json

}
