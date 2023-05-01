<?php

namespace App\Controller;

use App\Entity\Donation;
use App\Entity\Donater;
use App\Form\DonaterType;

use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\Boolean;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use MercurySeries\FlashyBundle\FlashyNotifier;

#[Route('/user/donater')]
class DonaterConntrollerController extends AbstractController
{
    #[Route('/', name: 'app_donater_conntroller_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $donaters = $entityManager
            ->getRepository(Donater::class)
            ->findByIdUser($this->getUser());

        return $this->render('donater_conntroller/index.html.twig', [
            'donaters' => $donaters,
        ]);
    }

    #[Route('/{id}/new', name: 'app_donater_conntroller_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, $id, FlashyNotifier $flashy): Response
    {
        $donater = new Donater();

        $form = $this->createForm(DonaterType::class, $donater);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $donater->setIdUser($this->getUser());
            $donater->setIdDonation($entityManager->getRepository(Donation::class)->findOneById($id));
            $existingDonation = $entityManager->getRepository(Donater::class)
                ->findOneBy(['idDonation' => $donater->getIdDonation(), 'idUser' => $donater->getIdUser()]);
            if ($this->updateDonationAmount($donater)) {
                if (!$existingDonation) {
                    $entityManager->persist($donater);
                    $entityManager->flush();
                } else {

                    $existingDonation->setAmount($existingDonation->getAmount() + $donater->getAmount());
                    $entityManager->persist($existingDonation);
                    $entityManager->flush();
                }
                $flashy->success('Donation sent !');
                return $this->redirectToRoute('app_donater_conntroller_index', [
                    'donater' => $donater,
                    'form' => $form,
                ], Response::HTTP_SEE_OTHER);
            } else {

                $flashy->error("Amount exceeded!");
            }
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



    private function updateDonationAmount(Donater $donater): bool
    {

        $currentAmount = $donater->getIdDonation()->getAmount();
        $newAmount = $currentAmount - $donater->getAmount();

        if ($newAmount >= 0) {
            $donation = $donater->getIdDonation();
            $donation->setAmount($newAmount);
            $em = $this->getDoctrine()->getManager();
            $em->persist($donation);
            $em->flush();
            return True;
        } else {
            return false;
        }
    }
}
