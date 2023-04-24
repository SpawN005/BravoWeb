<?php

namespace App\Form;

use App\Entity\Reservation;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\Event;
use App\Entity\User;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Validator\Constraints\Range;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class ReservationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('isConfirmed')
            ->add('nb_place', IntegerType::class, [
                'attr' => ['min' => 1, 'max' => 10],
                'constraints' => [new Range(['min' => 1, 'max' => 10])]
            ])
            

            ->add('id_event',EntityType::class, [
                'class'=>Event::class,
                'choice_label' => 'title',
               
            ])
            ->add('id_participant',EntityType::class, [
                'class'=>User::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->where('u.id = :id')
                        ->setParameter('id', 5);
                },
                
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
