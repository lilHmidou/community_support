<?php

namespace App\Form;

use App\Entity\Etudiant;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EtudiantFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('LevelStudies', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'required' => false,
                'label' => 'Niveau d\'études'
            ])
            ->add('Disabilty', TextareaType::class, [
                'attr' => ['class' => 'form-control'],
                'required' => false,
                'label' => 'Disabilty'
            ]);
        /*
        ->add('LevelStudies', TextType::class, [
        'attr' => ['class' => 'form-control'],
        'required' => false,
        'label' => 'Niveau d\'études'
    ])
        ->add('Disabilty', TextareaType::class, [
            'attr' => ['class' => 'form-control'],
            'required' => false,
            'label' => 'Handicap'
        ]);
        */
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Etudiant::class,
        ]);
    }
}