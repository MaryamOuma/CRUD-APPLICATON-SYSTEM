<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AgentFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('firstName', TextType::class, [
            'label' => 'First Name',
            'attr' => ['class' => 'form-control', 'placeholder' => 'Enter first name'],
        ])
        ->add('lastName', TextType::class, [
            'label' => 'Last Name',
            'attr' => ['class' => 'form-control', 'placeholder' => 'Enter last name'],
        ])
        ->add('DebutDate', DateType::class, [
            'label' => 'Debut Date',
            'attr' => ['class' => 'form-control', 'placeholder' => 'Enter date of debut'],
        ])
        ->add('EndDate', DateType::class, [
            'label' => 'End Date',
            'attr' => ['class' => 'form-control', 'placeholder' => 'Enter last date'],
        ])
        ->add('BirthDate', DateType::class, [
            'label' => 'Birth Date',
            'attr' => ['class' => 'form-control', 'placeholder' => 'Enter Birth date'],
        ])
        ->add('PlaceofBirth', TextType::class, [
            'label' => 'Place of Birth',
            'attr' => ['class' => 'form-control', 'placeholder' => 'Enter Place of Birth'],
        ]);


    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
