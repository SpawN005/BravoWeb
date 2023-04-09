<?php

namespace App\Form;

use App\Entity\Event;
use App\Entity\EventCategorie;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('description')
            ->add('nb_placeMax')
            ->add('date_beg')
            ->add('date_end')
            ->add('image', FileType::class, [
                'required' => true,
                'label' => 'Profile Picture',
                'data_class' => null,    
            ])
            ->add('categorie', EntityType::class, [
                'class'=>EventCategorie::class,
                'choice_label' => 'nom',
               
            ])
        ;
        $builder->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) {
            $user = $event->getData();
            $imageFile = $user->getImage();

            if ($imageFile instanceof UploadedFile) {
                $newFilename = uniqid().'.'.$imageFile->guessExtension();

                // Move the file to the directory where images are stored
                try {
                    $imageFile->move(
                        // $this->getParameter('images_directory'),
                        $this->params->get('images_directory'), 
                        $newFilename
                    );
                } catch (FileException $e) {
                    // Handle the exception
                }

                $user->setImage($newFilename);
            }
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
