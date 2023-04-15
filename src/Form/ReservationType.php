<?php

namespace App\Form;

use App\Entity\Reservation;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\Event;
use App\Entity\User;

class ReservationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('isConfirmed')
            ->add('nb_place')
            ->add('id_event',EntityType::class, [
                'class'=>Event::class,
                'choice_label' => 'title',
               
            ])
            ->add('id_participant',EntityType::class, [
                'class'=>User::class,
                'choice_label' => 'email',
               
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
        ]);
    }
}
