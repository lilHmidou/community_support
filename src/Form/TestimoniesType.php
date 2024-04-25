<?php

namespace App\Form;

use App\Entity\Testimonies;
use App\Validator\BanWord;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TestimoniesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('author', TextType::class)
            ->add('content', TextareaType::class, [
                'constraints' => [
                    new BanWord()
                ]
            ])
            ->add('date', DateType::class, [
                'widget' => 'single_text' // this is important for the date picker to work
            ])
            ->add('save', SubmitType::class, ['label' => 'Submit Testimonies']);
    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Testimonies::class,
        ]);
    }
}
