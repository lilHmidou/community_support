<?php

namespace App\Form;

use App\Entity\FAQ;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FAQType extends AbstractType
{ //Will be used to create a form for the FAQ entity in the admin panel
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('question')
            ->add('answer')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => FAQ::class,
        ]);
    }
}
