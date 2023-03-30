<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\Sortie;
use App\Entity\Lieu;
use App\Entity\Ville;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AjouterSortieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('dateHeureDebut', DateTimeType::class,
                [
                    'label' => "Date de l'activité : ",
                    'widget' => 'single_text',
                    'html5'=>true,
                ])
            ->add('dateLimiteInscription', DateTimeType::class,
                [
                    'label' => "Date limite d'inscription : ",
                    'widget' => 'single_text',
                    'html5'=>true
                ])
            ->add('duree', TimeType::class,
                [
                    'label' => "Durée : ",
                    'input' => 'timestamp'
                ])
            ->add('infosSortie', TextareaType::class,
                [
                    'label' => "Description et infos : ",
                    'attr' => ['rows' => 3]
                ])
            ->add('nbInscriptionMax', NumberType::class,
                [
                    'label' => "Nombre de places : ",
                    'attr' => ['min' => 1]
                ])
            ->add('siteOrganisateur', EntityType::class,
                [
                    'label' => "Campus : ",
                    'class' => Campus::class,
                    'choice_label' => 'nom'
                ])
            ->add('lieu', EntityType::class,
                [
                    'label' => "Lieu : ",
                    'class' => Lieu::class,
                    'required' => true,
                    'multiple' => false,
                    'choice_label' => function (Lieu $lieu) { return $lieu->getNom().'-'.$lieu->getRue().'-'.$lieu->getVille()->getCodePostal(); }

                ]);

    }

    public function configureOptions(OptionsResolver $resolver): void
        {
            $resolver->setDefaults([
                'data_class' => Sortie::class,
            ]);
        }
}
