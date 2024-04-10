<?php

namespace App\Form;

use App\Entity\Mentor;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;

class MentorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('domain', TextType::class, [
                'attr' => [
                    'class' => 'form-style',
                    'placeholder' => 'Domaine d\'études'
                ],
                'label' => '<i class="fa-solid fa-graduation-cap"></i>',
                'label_html' => true,
            ])
            ->add('learningChoice', ChoiceType::class, [
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
                    // Ajoutez autant d'options que nécessaire
                ],
                'attr' => [
                    'class' => 'form-style',
                ],
                'placeholder' => 'Choisissez ce que vous voulez enseignez',
                'label' => '<i class="fa-solid fa-book"></i>',
                'label_html' => true,
            ])
            ->add('comments', TextType::class, [
                'attr' => [
                    'class' => 'form-style',
                    'placeholder' => 'Commentaires'
                ],
                'label' => '<i class="fa-solid fa-comment"></i>',
                'label_html' => true,
            ])
            ->add('levelExperience', ChoiceType::class, [
                'choices' => [
                    'Débutant' => 'Débutant',
                    'Intermédiaire' => 'Intermédiaire',
                    'Avancé' => 'Avancé',
                    'Expert' => 'Expert',
                    // Ajoutez autant d'options que nécessaire
                ],
                'attr' => [
                    'class' => 'form-style',
                ],
                'placeholder' => 'Niveau d\'expérience',
                'label' => '<i class="fa-solid fa-graduation-cap"></i>',
                'label_html' => true,
            ])
            ->add('availability', ChoiceType::class, [
                'choices' => [
                    'Matin' => 'Matin',
                    'Après-midi' => 'Après-midi',
                    'Soir' => 'Soir',
                    'Week-end' => 'Week-end',
                    // Ajoutez autant d'options que nécessaire
                ],
                'attr' => [
                    'class' => 'form-style',
                ],
                'placeholder' => 'Disponibilité',
                'label' => '<i class="fa-solid fa-clock"></i>',
                'label_html' => true,
            ])
            ->add('doc', FileType::class, [
                'label' => 'Document (PDF)',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'application/pdf',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid PDF document',
                    ])
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Mentor::class,
        ]);
    }
}