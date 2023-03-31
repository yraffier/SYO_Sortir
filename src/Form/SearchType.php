<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\SearchData;
use ContainerJob7dle\getSortieService;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'placeholder' => 'Rechercher'
                ]
            ])
            ->add('campus', EntityType::class, [
                'label' => false,
                'required' => false,
                'class' => Campus::class,
                'placeholder' => '- Selectionnez votre région -',
            ])
            ->add('dateDebut', DateTimeType::class, [
                'placeholder' => [
                    'year' => 'Année', 'month' => 'Mois', 'day' => 'Jour',
                    'hour' => 'Heures', 'minute' => 'Minutes', 'second' => 'Secondes',
                ],
                'html5'=>true,
                'required' => false,
            ])
            ->add('dateFin', DateTimeType::class, [
                'placeholder' => [
                    'year' => 'Année', 'month' => 'Mois', 'day' => 'Jour',
                    'hour' => 'Heures', 'minute' => 'Minutes', 'second' => 'Secondes',
                ],
                'html5'=>true,
                'required' => false,
            ])
            ->add('organisateur', CheckboxType::class, [
                'label' => 'Sorties dont je suis l\'organisateur/trice',
                'data' => true,
                'required' => false,

            ])
            ->add('inscrit', CheckboxType::class, [
                'label' => 'Sorties auxquelles je suis incrit/e',
                'data' => true,
                'required' => false,
            ])
            ->add('NonInscrit', CheckboxType::class, [
                'label' => 'Sorties auxquelles je ne suis pas incrit/e',
                'data' => true,
                'required' => false,
            ])
            ->add('sortiePassee', CheckboxType::class, [
                'label' => 'Sorties passées',
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SearchData::class,
        ]);
    }
}
