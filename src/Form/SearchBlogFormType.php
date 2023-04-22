<?php

namespace App\Form;

use App\Entity\Blog;
use App\Entity\CategorieBlog;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class SearchBlogFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

        ->add('title',TextType::class, [
            'attr' => ['placeholder' => 'Rechercher...'],
                'constraints' => [new NotBlank()]
            ])

        ->add('categorie', EntityType::class, [
            'class' => CategorieBlog::class,
            'required' => false,
            'placeholder' => 'Choose a category',
            'choice_label' => 'NomCategorie',
        ])
        
        ->add('Search',SubmitType::class)
    ;
}
    

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Blog::class,
            'method' => 'GET',
        ]);
    }
}
