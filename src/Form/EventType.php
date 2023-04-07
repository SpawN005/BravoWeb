<?php

namespace App\Form;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use App\Entity\Event;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\CategorieEvent;
class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('description')
            ->add('nbPlacemax')
            ->add('dateBeg')
            ->add('dateEnd')
            ->add('url', FileType::class, [
                'required' => true,
                'label' => 'Event picture',
                'data_class' => null, // Définir data_class à null pour éviter l'erreur
            ])

            ->add('categorie',EntityType::class,
            ['class'=>CategorieEvent::class,
            'choice_label'=>'nomCategorieEvent'])

        ;
       
        $builder->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) {
            $events = $event->getData();
            $imageFile = $events->getUrl();

            if ($imageFile instanceof UploadedFile) {
                $newFilename = uniqid().'.'.$imageFile->guessExtension();

                // Move the file to the directory where images are stored
                try {
                    $imageFile->move(
                        $this->getParameter('images_directory'),
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
