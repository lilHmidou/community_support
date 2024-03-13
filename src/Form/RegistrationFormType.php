<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;


class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('FirstName', TextType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Prénom',
            ])
            ->add('LastName', TextType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Nom',
            ])
            ->add('Address', TextType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Adresse',
            ])
            ->add('PhoneNumber', TextType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Numéro de téléphone',
            ])
            ->add('Gender', ChoiceType::class, [
                'attr' => [
                    'class' => 'form-select'
                ],
                'label' => 'Genre',
                'choices' => [
                    'Homme' => 'homme',
                    'Femme' => 'femme',
                ],
                'expanded' => true,  // Cette option génère des boutons radio au lieu d'une liste déroulante
                'multiple' => false, // Sélection unique
            ])

            ->add('DOB', BirthdayType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Date de naissance',
            ])

            ->add('email', EmailType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Email',
            ])

            ->add('plainPassword', PasswordType::class, [
                    // instead of being set onto the object directly,
                    // this is read and encoded in the controller
                    'mapped' => false,
                    'attr' => [
                        'autocomplete' => 'new-password',
                        'class' => 'form-control'
                    ],
                    'constraints' => [
                        new Length([
                            'min' => 8,
                            'minMessage' => 'Votre mot de passe doit comporter au moins {{ limit }} caractères.',
                            // max length allowed by Symfony for security reasons
                            'max' => 255,
                        ]),
                        new Regex([
                            'pattern' => '/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@$!%*?&.])[A-Za-z\d@$!%*?&.]{8,}$/',
                            'message' => 'Votre mot de passe doit contenir au moins une lettre majuscule, une lettre minuscule, un chiffre et un caractère spécial "@$!%*?&.".',
                        ]),
                    ],
                    'label' => 'Mot de passe',
                ])
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'attr' => [
                    'class' => 'custom-checkbox'
                ],
                'constraints' => [
                    new IsTrue([
                        'message' => 'Tu dois accepter les termes des conditions.',
                    ]),
                ],
                'label' => 'Accepter les conditions d\'utilisation : ',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
