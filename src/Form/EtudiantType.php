<?php

namespace App\Form;

use App\Entity\Etudiant;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints as Assert;

class EtudiantType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Domain', TextType::class, [
                'attr' => [
                    'class' => 'form-style',
                    'placeholder' => 'Domaine d\'études'
                ],
                'label' => '<i class="input-icon fa-solid fa-book"></i>',
                'label_html' => true,
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length([
                        'max' => 100
                    ])
                ],
            ])
            ->add('LearningChoice', ChoiceType::class, [
                'choices' => [
                    'Langues étrangères' => 'Langues étrangères',
                    'Informatique et programmation' => 'Informatique et programmation',
                    'Marketing et communication' => 'Marketing et communication',
                    'Gestion et finance' => 'Gestion et finance',
                    'Sciences et technologies' => 'Sciences et technologies',
                    'Arts et design' => 'Arts et design',
                    'Santé et bien-être' => 'Santé et bien-être',
                    'Droit et justice' => 'Droit et justice',
                    'Autre' => 'Autre',
                ],
                'attr' => [
                    'class' => 'form-style',
                ],
                'placeholder' => 'Choisissez ce que vous voulez apprendre',
                'label' => '<i class="input-icon fa-solid fa-chalkboard-teacher"></i>',  // Icône de professeur
                'label_html' => true,
            ])
            ->add('Comments', TextType::class, [
                'attr' => [
                    'class' => 'form-style',
                    'placeholder' => 'Commentaires'
                ],
                'label' => '<i class="input-icon fa-solid fa-comment"></i>', // Icône de commentaire
                'label_html' => true,
                'constraints' => [
                    new Assert\Length([
                        'max' => 255
                    ])
                ],
            ])
            ->add('LevelStudies', ChoiceType::class, [
                'choices' => [
                    'Bac' => 'Bac',
                    'Bac +1' => 'Bac +1',
                    'Bac +2' => 'Bac +2',
                    'Licence' => 'Licence',
                    'Master' => 'Master',
                    'Doctorat' => 'Doctorat',
                    'Autre' => 'Autre',
                ],
                'attr' => [
                    'class' => 'form-style',
                ],
                'placeholder' => 'Votre niveau d\'études : ',
                'label' => '<i class="input-icon fa-solid fa-graduation-cap"></i>', // Icône de chapeau de diplômé
                'label_html' => true,
            ])
            ->add('Disability', ChoiceType::class, [
                'attr' => [
                    'class' => 'form-select',
                ],
                'choices' => [
                    'Oui' => true,
                    'Non' => false,
                ],
                'label' => false,
                'expanded' => true,  // Cette option génère des boutons radio au lieu d'une liste déroulante
                'multiple' => false, // Sélection unique
            ])
            ->add('Doc', FileType::class, [
                'attr' => [
                    'class' => 'form-style',
                ],
                'label' => 'Télécharger votre lettre de Motivation : ',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'application/pdf',
                        ],
                        'mimeTypesMessage' => 'Veuillez télécharger un document PDF valide',
                    ])
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Etudiant::class,
        ]);
    }
}