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
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;



class DonationController extends AbstractController
{
    #[Route('/user/donation', name: 'app_donation', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {

        $donations = $entityManager
            ->getRepository(Donation::class)
            ->findByOwner($this->getUser());
        return $this->render('donation/index.html.twig', [
            'controller_name' => 'DonationController',
            'donation' => $donations
        ]);
    }
    #[Route('/user/donation/show', name: 'app_donation_show', methods: ['GET'])]
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
    #[Route('/admin/donation/back', name: 'app_donation_back', methods: ['GET'])]
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

    #[Route('/artiste/donation/add', name: 'app_donation_add', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $donation = new Donation();
        $form = $this->createForm(DonationType::class, $donation);
        $form->handleRequest($request);
        $donation->setOwner($this->getUser());

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
    #[Route('/artiste/updateDonation/{id}', name: 'app_donation_update')]
    public function updateDonation(ManagerRegistry $doctrine, $id, Request $req): Response
    {
        $em = $doctrine->getManager();
        $Donation = $doctrine->getRepository(Donation::class)->find($id);
        $form = $this->createForm(DonationType::class, $Donation);

        $form->handleRequest($req);
        if ($form->isSubmitted()) {
            $em->persist($Donation);
            $em->flush();
            return $this->redirectToRoute('app_donation');
        }
        return $this->renderForm('donation/add.html.twig', ['form' => $form]);
    }
    #[Route('/artiste/deleteDonation/{id}', name: 'app_donation_delete')]
    public function deleteDonation(ManagerRegistry $doctrine, $id): Response
    {
        $em = $doctrine->getManager();
        $donation = $doctrine->getRepository(Donation::class)->find($id);
        $em->remove($donation);
        $em->flush();
        return $this->redirectToRoute('app_donation');
    }

    #[Route('/artiste/donationStats', name: 'app_donation_stats')]
    public function DonationStats(ChartBuilderInterface $chartBuilder, EntityManagerInterface $entityManager): Response
    {
        $donations = $entityManager
            ->getRepository(Donation::class)
            ->findByOwner($this->getUser());
        $chart = $chartBuilder->createChart(Chart::TYPE_LINE);

        $chart->setData([
            'labels' => ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
            'datasets' => [
                [
                    'label' => 'My First dataset',
                    'backgroundColor' => 'rgb(255, 99, 132)',
                    'borderColor' => 'rgb(255, 99, 132)',
                    'data' => [0, 10, 5, 2, 20, 30, 45],
                ],
            ],
        ]);

        $chart->setOptions([
            'scales' => [
                'y' => [
                    'suggestedMin' => 0,
                    'suggestedMax' => 100,
                ],
            ],
        ]);

        return $this->render('donation/stats.html.twig', [
            'chart' => $chart, 'donation' => $donations
        ]);
    }
}
