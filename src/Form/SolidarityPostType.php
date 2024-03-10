<?php

namespace App\Form;

use App\Entity\Post;
use App\Entity\User;
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
            ->add('description', TextareaType::class)
            ->add('created_at_p', HiddenType::class, [
                'mapped' => false,
            ])
            ->add('location', TextType::class)
            ->add('category', ChoiceType::class, [
                'choices' => [
                    'Category 1' => 'category1',
                    'Category 2' => 'category2',
                    // Ajoutez d'autres catégories ici si nécessaire
                ]])
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
