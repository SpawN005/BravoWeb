<?php

namespace App\Form;

use App\Entity\Reclamation;
use App\Entity\TypeReclamation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;



class ReclamationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'TypeReclamation',
                EntityType::class,
                [
                    'class' => TypeReclamation::class, // liste déroulante depuis
                    'choice_label' => 'TypeReclamation'
                ]
            ) // aabineha b name

            ->add('title')
            ->add('description', TextareaType::class)
            //->add('Save',SubmitType::class);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reclamation::class,
        ]);
    }
}
