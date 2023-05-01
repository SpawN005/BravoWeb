<?php

namespace App\Form;

use App\Entity\Donation;
use App\Entity\User;
use App\Entity\CategorieDonation;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints as Assert;

class DonationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('description')
            ->add('dateCreation')
            ->add('dateExpiration')
            ->add('amount')
            ->add('Categorie', EntityType::class, [
            'class' => CategorieDonation::class,
            'choice_label' => 'nomcategorie',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Donation::class,
        ]);
    }
}
