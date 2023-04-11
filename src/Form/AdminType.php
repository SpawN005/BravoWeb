<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Form\Extension\Core\Type\TelType;
class AdminType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
           
        ->add('firstName')
        ->add('lastName')
        ->add('email')
        ->add('password',RepeatedType::class,[
            'type'=>Passwordtype::class,
            'first_options'=>['label'=>'Password'],
            'second_options'=>['label'=>'Confirm Password'],

       'constraints' => [
            new NotBlank(),
            new Length([
                'min' => 8,
                'max' => 255,
                'minMessage' => 'Your password must be at least {{ limit }} characters long',
                'maxMessage' => 'Your password cannot be longer than {{ limit }} characters',
            ]),
            new Regex([
                'pattern' => '/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/',
                'message' => 'Your password must contain at least one letter and one number',
            ]),
        ],
    ])
                   
    ->add('phone', TelType::class, [
            
        'label' => 'Phone Number',
        'required' => true,
        'attr' => [
            'placeholder' => '+216', // replace X with actual digits
        ],
        'constraints' => [
            new NotBlank(),
            new Length([
                'min' => 8,
                'max' => 8,
                'minMessage' => 'Your phone number must be at least {{ limit }} numbers',
                'maxMessage' => 'Your phone number cannot be longer than {{ limit }} numbers',
            ]),
            
            ]
    ])
           

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
