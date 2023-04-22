<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

use Symfony\Component\Validator\Context\ExecutionContextInterface; 
use Symfony\Component\Validator\Constraints\Callback;



class AvisReclamationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        
        $builder
        ->add('satisfait', ChoiceType::class, [
            'choices' => [
                'Oui' => true,
                'Non' => false,
            ],
            'expanded' => true,
        ])
        ->add('Note', ChoiceType::class, [
            'choices' => [
                '1' => '1',
                '2' => '2',
                '3' => '3',
                '4' => '4',
                '5' => '5',
                
            ],
            'expanded' => true,
            'multiple' => false,
            'required' => false, // Définir le champ comme non obligatoire
            'constraints' => [
                new Callback([
                    'callback' => function ($value, ExecutionContextInterface $context) {
                        $form = $context->getRoot();
                        $data = $form->getData();
                        // ajouter contrainte si satisfait note >4
                        if ($data['satisfait'] && in_array($value, ['1', '2', '3'])) {
                            $context->addViolation("Si vous êtes satisfait, veuillez sélectionner une note de 4 ou 5.");
                        }

                        // Ajouter une contrainte si non stisfait la note doit etre <4
                        if (!$data['satisfait'] && in_array($value, ['4', '5'])) {

                        }
                    },
                ]),
            ],
           
        ])
      ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([

        ]);
    }
}
