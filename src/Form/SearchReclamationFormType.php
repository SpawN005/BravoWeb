<?php

namespace App\Form;

use App\Entity\Reclamation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class SearchReclamationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Recherche par titre',
                'required' => false,
            ])
            ->add('etat', ChoiceType::class, [
                'label' => 'Recherche par état',
                'required' => false,
                'choices' => [
                    'on hold' => 'on hold',
                    'processing' => 'processing',
                    'treated' => 'treated',
                    
                ],
            ])
            ->add('dateCreation', DateTimeType::class, [
                'label' => 'Recherche par date de création',
                'required' => false,
                'widget' => 'single_text',
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Rechercher',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // 'data_class' => Reclamation::class,
            // 'method' => 'GET',
        ]);
    }
}
