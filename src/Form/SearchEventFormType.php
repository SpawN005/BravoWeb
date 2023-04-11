<?php

namespace App\Form;

use App\Entity\EventCategorie;
use App\Entity\Event;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class SearchEventFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
           
            ->add('nbPlaceMax', NumberType::class, [
                'label' => 'Maximum number of places',
                'required' => false,
            ])
            ->add('categorie', EntityType::class, [
                'class' => EventCategorie::class,
                'required' => false,
                'placeholder' => 'Choose a category',
                'choice_label' => 'nom',
            ])
            ->add('Search',SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
            'method' => 'GET',
        ]);
    }
}
