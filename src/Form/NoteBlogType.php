<?php

namespace App\Form;

use App\Entity\NoteBlog;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Validator\Constraints\Range;


class NoteBlogType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('note', IntegerType::class, [
            'label' => 'Note (entre 0 et 5)',
            'constraints' => [
                new Range([
                    'min' => 0,
                    'max' => 5,
                    'notInRangeMessage' => 'La note doit être entre {{ min }} et {{ max }}.',
                ]),
            ],
        ])

            ->add('Add',SubmitType::class)

           
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => NoteBlog::class,
        ]);
    }
}
