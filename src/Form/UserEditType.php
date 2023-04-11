<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;   
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Form\Extension\Core\Type\TelType;
class UserEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            
        
       
            ->add('email', null, [
                'constraints' => [
                    new Regex([
                        'pattern' => '/^\S+@\S+\.\S+$/',
                    ]),
                ],
            ])
            ->add('image', FileType::class, [
                'label' => 'image du produit',
    
                // unmapped means that this field is not associated to any entity property
                'mapped' => false,
    
                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => false,
    
                // unmapped fields can't define their validation using annotations
                // in the associated entity, so you can use the PHP constraint classes
                'constraints' => [
                    new File([
                        'maxSize' => '5000k',
                        'mimeTypes' => [
                            'image/jpg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid image',
                    ])
                ],
            ]) 
            ->add('phone', TelType::class, [
                'label' => 'Phone Number',
                'required' => true,
                'attr' => [
                    'placeholder' => 'xxxxxxxx', // replace x with actual digits
                    'pattern' => '\d{8}', // 8 digits only
                ],
                'constraints' => [
                    new NotBlank(),
                    new Length([
                        'min' => 8,
                        'max' => 8,
                        'exactMessage' => 'Your phone number must have exactly {{ limit }} digits',
                    ]),
                    new Regex([
                        'pattern' => '/^\d+$/',
                        'message' => 'Your phone number should contain digits only',
                    ]),
                ],
            ])
            
            
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
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
