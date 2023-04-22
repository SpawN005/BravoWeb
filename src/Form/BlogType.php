<?php

namespace App\Form;

use App\Entity\Blog;
use App\Entity\CategorieBlog;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;



class BlogType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder


        ->add('author', EntityType::class, [
            'class'=>User::class,
            'choice_label' => 'name',
           
        ])
        
            ->add('title')

            ->add('description')
            
            ->add('image', FileType::class, [
                'required' => true,
                'label' => 'Profile Picture',
                'data_class' => null,    
            ])
            ->add('content')

            ->add('categorie', EntityType::class, [
                'class'=>CategorieBlog::class,
                'choice_label' => 'NomCategorie',
               
            ])

            

            ->add('Create',SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Blog::class,
        ]);
    }
}
