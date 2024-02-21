<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InscriptionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('FirstName', TextType::class, [
                'label' => 'Prénom',
            ])
            ->add('LastName', TextType::class, [
                'label' => 'Nom',
            ])

            ->add('Adress', TextType::class, [
                'label' => 'Adresse',
            ])
            ->add('Phone', TextType::class, [
                'label' => 'Téléphone',
            ])
            ->add('gender', ChoiceType::class, [
                'label' => 'Genre',
                'choices' => [
                    'Homme' => 'homme',
                    'Femme' => 'femme',
                ],
                'expanded' => true,  // Cette option génère des boutons radio au lieu d'une liste déroulante
                'multiple' => false, // Sélection unique
            ])
            ->add('DOB', BirthdayType::class, [
                'label' => 'Date de naissance',
            ])
            ->add('Email', EmailType::class, [
                'label' => 'Email',
            ])
            ->add('Password', PasswordType::class, [
                'label' => 'Mot de passe',
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Créer un compte',
                'attr' => [
                    'class' => 'button button-submit',
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
