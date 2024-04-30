<?php

namespace App\Form;

use App\Entity\ContactMessage;
use App\Validator\BanWord;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactMessageFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('topic', TextType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Sujet',
            ])
            ->add('contentCM', TextareaType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Contenu du message',
                'constraints' => [
                    new BanWord()
                ]
            ]);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ContactMessage::class,
        ]);
    }
}
