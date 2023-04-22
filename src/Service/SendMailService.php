<?php
namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;

class SendMailService
{
    // private $mailer;

    // public function __construct(MailerInterface $mailer)
    // {
    //     $this->mailer = $mailer;
    // }

    // public function send(string $fromEmail,string $fromName, string $to, string $subject, string $template, array $context): void
    // {
    //     // On crée le mail
    //     $email = (new TemplatedEmail())
    //     ->from(new Address('myriam123.hammi@gmail.com', $fromName))
    //     ->to($to)
    //     ->subject($subject)
    //     ->htmlTemplate('email/' . $template .'.html.twig')
    //     ->context($context);

    //     // On envoie le mail
    //     $this->mailer->send($email);
    // }

    // public function sendMail(string $fromEmail,string $fromName, string $to, string $subject, string $template, array $data = []): void
    // {
    //     // On crée le mail
    //     $email = (new TemplatedEmail())
    //     ->from(new Address('myriam123.hammi@gmail.com', $fromName))
    //     ->to($to)
    //     ->subject($subject)
    //     ->htmlTemplate('email/' . $template .'.html.twig')
    //     ->context($data);

    //     // On envoie le mail
    //     $this->mailer->send($email);
    // }
    private $mailer;
    private $entityManager;

    public function __construct(MailerInterface $mailer, EntityManagerInterface $entityManager)
    {
        $this->mailer = $mailer;
        $this->entityManager = $entityManager;
    }

    public function send(string $fromEmail,string $fromName, string $to, string $subject, string $template, array $context): void
    {
        // On crée le mail
        $email = (new TemplatedEmail())
        ->from(new Address('meriam123.hammi@gmail.com', $fromName))
        ->to($to)
        ->subject($subject)
        ->htmlTemplate('email/' . $template .'.html.twig')
        ->context($context);

        // On envoie le mail
        $this->mailer->send($email);
    }

    public function sendMail(string $fromEmail,string $fromName,  string $recipientEmail,string $subject, string $template, array $data = []): void
    {
        // Récupérer l'utilisateur ayant l'id 5
        $user = $this->entityManager->getRepository(User::class)->find(5);

        if (!$user) {
            throw new \Exception('Utilisateur introuvable avec l\'id 5');
        }

        // On crée le mail
        $email = (new TemplatedEmail())
        ->from(new Address('meriam123.hammi@gmail.com', $fromName))
        ->to(new Address($recipientEmail))
        ->subject($subject)
        ->htmlTemplate('email/' . $template .'.html.twig')
        ->context($data);

        // On envoie le mail
        $this->mailer->send($email);
    }


}