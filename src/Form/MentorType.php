<?php

namespace App\Form;

use App\Entity\Mentor;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class MentorType extends AbstractType
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
                'placeholder' => 'Choisissez ce que vous voulez enseigner',
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
            ])
            ->add('LevelExperience', ChoiceType::class, [
                'choices' => [
                    'Débutant' => 'Débutant',
                    'Intermédiaire' => 'Intermédiaire',
                    'Avancé' => 'Avancé',
                    'Expert' => 'Expert',
                ],
                'attr' => [
                    'class' => 'form-style',
                ],
                'placeholder' => 'Niveau d\'expérience',
                'label' => '<i class="input-icon fa-solid fa-graduation-cap"></i>', // Icône de chapeau de diplômé
                'label_html' => true,
            ])
            ->add('Availability', ChoiceType::class, [
                'choices' => [
                    'Matin' => 'Matin',
                    'Après-midi' => 'Après-midi',
                    'Soir' => 'Soir',
                    'Week-end' => 'Week-end',
                ],
                'attr' => [
                    'class' => 'form-style',
                ],
                'placeholder' => 'Disponibilité',
                'label' => '<i class="input-icon fa-solid fa-clock"></i>', // Icône de montre ou horloge
                'label_html' => true,
            ])
            ->add('Doc', FileType::class, [
                'attr' => [
                    'class' => 'form-style',
                ],
                'label' => 'Télécharger votre CV : ',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'application/pdf',
                        ],
                        'mimeTypesMessage' => 'Veuillez télécharger un document PDF valide',
                    ]),
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
