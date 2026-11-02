<?php

namespace App\Form;

use App\Entity\Location;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LocationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // Field names must match entity property names exactly (capitalized)
        $builder
            ->add('Name', TextType::class, [
                'label' => 'City',
                'attr' => ['placeholder' => 'Enter city name'],
            ])
            ->add('Country', ChoiceType::class, [
                'choices' => [
                    'Poland' => 'PL',
                    'Germany' => 'DE',
                    'France' => 'FR',
                    'Spain' => 'ES',
                    'Italy' => 'IT',
                    'United Kingdom' => 'GB',
                    'United States' => 'US',
                ],
            ])
            ->add('Latitude', NumberType::class, [
                'scale' => 7,
                'html5' => true,
                'input' => 'string',
                'attr' => ['step' => 'any'],
            ])
            ->add('Longitude', NumberType::class, [
                'scale' => 7,
                'html5' => true,
                'input' => 'string',
                'attr' => ['step' => 'any'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Location::class,
        ]);
    }
}
