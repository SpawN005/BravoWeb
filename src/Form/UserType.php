<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Form\Extension\Core\Type\TelType;
class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName', null, [
                'constraints' => [
                    new Regex([
                        'pattern' => '/^[a-zA-Z]+$/',
                        'message' => 'The First name should contain letters only',
                    ]),
                ],
            ])
            ->add('lastName', null, [
                'constraints' => [
                    new Regex([
                        'pattern' => '/^[a-zA-Z]+$/',
                        'message' => 'The Last name should contain letters only',
                    ]),
                ],
            ])
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
                    'exactMessage' => 'Your phone number must be exactly {{ limit }} numbers',

                ]),
                
                new Regex([
                    'pattern' => '/^\d+$/',
                    'message' => 'Your phone number should contain digits only',
                ]),
                
                
                ]
        ])
            ->add('roles', ChoiceType::class, [
                'required' => true,
                'multiple' => false,
                'expanded' => false,
                'placeholder' => 'You will be joining us as : ',
                'choices'  => [
                    'ADMIN' => 'ROLE_ADMIN',
                    'USER' => 'ROLE_USER',
                    'ARTISTE'=> 'ROLE_ARTISTE'

                ],
            ]) ;


        $builder->get('roles')
            ->addModelTransformer(new CallbackTransformer(
                function ($rolesArray) {
                    // transform the array to a string
                    return count($rolesArray)? $rolesArray[0]: null;
                },
                function ($rolesString) {
                    // transform the string back to an array
                    return [$rolesString];
                }
            ));

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
