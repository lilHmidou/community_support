<?php

namespace App\Form;

use App\Entity\Post;
use App\Entity\User;
use App\Validator\BanWord;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SolidarityPostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class)
            ->add('description', TextareaType::class, [
                'constraints' => [
                    new BanWord()
                ]
            ])
            ->add('created_at_p', HiddenType::class, [
                'mapped' => false,
            ])
            ->add('location', TextType::class)
            ->add('category', ChoiceType::class, [
                'choices' => [
                    'Alimentaire' => 'alimentaire',
                    'Communautaire' => 'communautaire',
                    'Environnemental' => 'environnemental',
                    'Sensibilisation' => 'sensibilisation',
                    'Autre' => 'autre',
                ]])
            ->add('nb_like', HiddenType::class, [
                'mapped' => false,
            ])
            ->add('user_id', HiddenType::class, [
                'mapped' => false,
            ])
            ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}
