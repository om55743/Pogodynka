<?php

namespace App\Form;

use App\Entity\Forecast;
use App\Entity\Location;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ForecastType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // Field names must match entity property names exactly (capitalized)
        $builder
            ->add('location', EntityType::class, [
                'class' => Location::class,
                'choice_label' => 'Name', // show city name instead of id
            ])
            ->add('Date', DateType::class, [
                'widget' => 'single_text',
            ])
            ->add('Temperature', NumberType::class, [
                'label' => 'Temperature (°C)',
                'scale' => 1,
            ])
            ->add('Humidity', IntegerType::class, [
                'label' => 'Humidity (%)',
            ])
            ->add('Rain', IntegerType::class, [
                'label' => 'Chance for precipitation (%)',
            ])
            ->add('Wind', IntegerType::class, [
                'label' => 'Wind (m/s)',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Forecast::class,
        ]);
    }
}
