<?php

namespace App\Form\UserForm;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Regex;


class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('FirstName', TextType::class, [
                'attr' => [
                    'class' => 'form-style',
                    'placeholder' => 'Prénom'
                ],
                'label' => '<i class="input-icon uil uil-user"></i>',
                'label_html' => true,
            ])
            ->add('LastName', TextType::class, [
                'attr' => [
                    'class' => 'form-style',
                    'placeholder' => 'Nom'
                ],
                'label' => false,
            ])
            ->add('Address', TextType::class, [
                'attr' => [
                    'class' => 'form-style',
                    'placeholder' => 'Adresse'
                ],
                'label' => '<i class="input-icon uil uil-home"></i>',
                'label_html' => true,
            ])
            ->add('PhoneNumber', TextType::class, [
                'attr' => [
                    'class' => 'form-style',
                    'placeholder' => 'Numéro de téléphone'
                ],
                'label' => '<i class="input-icon uil uil-phone"></i>',
                'label_html' => true,
            ])
            ->add('Gender', ChoiceType::class, [
                'attr' => [
                    'class' => 'form-select',
                ],
                'label' => false,
                'choices' => [
                    'Homme' => 'homme',
                    'Femme' => 'femme',
                ],
                'expanded' => true,  // Cette option génère des boutons radio au lieu d'une liste déroulante
                'multiple' => false, // Sélection unique
            ])
            ->add('DOB', BirthdayType::class, [
                'attr' => [
                    'class' => 'form-style yellow-calendar',
                    'style' => 'margin-top:-15px; padding-left: 5px; width: 150px;' // Définissez la largeur maximale souhaitée
                ],
                'label' => 'Date de naissance',
            ])
            ->add('email', EmailType::class, [
                'attr' => [
                    'class' => 'form-style',
                    'placeholder' => 'Adresse e-mail'
                ],
                'label' => '<i class="input-icon uil uil-at"></i>',
                'label_html' => true,
            ])
            ->add('plainPassword', PasswordType::class, [
                'mapped' => false,
                'attr' => [
                    'autocomplete' => 'new-password',
                    'class' => 'form-style',
                    'placeholder' => 'Mot de passe',
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
                'label' => '<i class="input-icon uil uil-lock-alt"></i>',
                'label_html' => true,
                'help' => 'Votre mot de passe doit comporter au moins : ',
            ])
            ->add('confirmPassword', PasswordType::class, [
                'mapped' => false,
                'attr' => [
                    'autocomplete' => 'new-password',
                    'class' => 'form-style',
                    'placeholder' => 'Confirmer le mot de passe',
                ],
                'label' => '<i class="input-icon uil uil-lock-alt"></i>',
                'label_html' => true,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
