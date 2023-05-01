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
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Context\ExecutionContextInterface;



class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('description')
            ->add('nb_placeMax')
            // ->add('date_beg')

            ->add('date_beg', DateType::class, [
                'label' => 'Start Date',
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
                'attr' => [
                    'class' => 'my-custom-class',
                    'placeholder' => 'YYYY-MM-DD',
                ],
                'data' => new \DateTime(),
            ])
            // ->add('date_end')

            ->add('date_end', DateType::class, [
                'label' => 'Start Date',
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
                'attr' => [
                    'class' => 'my-custom-class',
                    'placeholder' => 'YYYY-MM-DD',
                ],
                'data' => new \DateTime(),

            ])
            ->add('image', FileType::class, [
                'required' => true,
                'label' => 'Profile Picture',
                'data_class' => null,
            ])
            ->add('categorie', EntityType::class, [
                'class' => EventCategorie::class,
                'choice_label' => 'nom',

            ]);
        // $builder->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) {
        //     $user = $event->getData();
        //     $imageFile = $user->getImage();

        //     if ($imageFile instanceof UploadedFile) {
        //         $newFilename = uniqid().'.'.$imageFile->guessExtension();

        //         // Move the file to the directory where images are stored
        //         try {
        //             $imageFile->move(
        //                 // $this->getParameter('images_directory'),
        //                 $this->params->get('images_directory'), 
        //                 $newFilename
        //             );
        //         } catch (FileException $e) {
        //             // Handle the exception
        //         }

        //         $user->setImage($newFilename);
        //     }
        // });










    }
    /*
    public function validateDates($data, ExecutionContextInterface $context)
{
    $dateBeg = $data->getDateBeg()->format('Y-m-d');
    $dateEnd = $data->getDateEnd()->format('Y-m-d');

    if ($dateBeg > $dateEnd) {
        $context->buildViolation('Start date must be before end date')
            ->atPath('date_beg')
            ->addViolation();
    }
}*/
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
