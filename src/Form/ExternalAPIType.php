<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ExternalAPIType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('latitude', NumberType::class, [
                'label' => 'Latitude',
                'html5' => true,
                'attr' => ['step' => 'any', 'placeholder' => 'e.g. 53.429'],
            ])
            ->add('longitude', NumberType::class, [
                'label' => 'Longitude',
                'html5' => true,
                'attr' => ['step' => 'any', 'placeholder' => 'e.g. 14.553'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}
