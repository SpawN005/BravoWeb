<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

use Symfony\Component\Form\FormFactoryInterface; 
use Symfony\Component\Validator\Context\ExecutionContextInterface; // Importer la classe ExecutionContextInterface
use Symfony\Component\Validator\Constraints\Callback;



class AvisReclamationFormType extends AbstractType
{
    private $formFactory; // Déclarer la propriété formFactory

    public function __construct(FormFactoryInterface $formFactory) // Injecter FormFactoryInterface dans le constructeur
    {
        $this->formFactory = $formFactory;
    }
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

                        if ($data['satisfait'] && in_array($value, ['1', '2', '3'])) {
                            $context->addViolation("Si vous êtes satisfait, veuillez sélectionner une note de 4 ou 5.");
                        }

                        // Ajouter une nouvelle validation pour empêcher la sélection de notes 4 ou 5 si "satisfait" est défini sur "Non"
                        if (!$data['satisfait'] && in_array($value, ['4', '5'])) {
                            $context->addViolation("Si vous êtes non satisfait, veuillez sélectionner une note 1 ou 2 ou 3.");
                        }
                    },
                ]),
            ],
            'choice_attr' => function ($choice, $key, $value) use ($builder) {
                $data = $builder->getData();
                $satisfait = isset($data['satisfait']) ? $data['satisfait'] : null;//si on clique sur oui ou non il prend cette valeur sinon null
                if ($satisfait && in_array($choice, ['1', '2', '3'])) {
                    return ['disabled' => 'disabled'];//  si satsifait on desactive la note 1 2 et 3
                }
                return [];
            },
            
            
        ])

      ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([

        ]);
    }
}
