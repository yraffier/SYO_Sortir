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
            ->add('dateHeureDebut',DateTimeType::class,
                [
                'label'=>"date de l'activité",
                'widget'=>'single_text',
                ]
            )
            ->add('duree',TimeType::class,[
                'input'=>'timestamp'
            ])
            ->add('dateLimiteInscription',DateTimeType::class,[
                'widget'=>'single_text',
            ])
            ->add('infosSortie',TextareaType::class,[
                'attr'=>['rows'=>5],
            ])
            ->add('nbInscriptionMax',NumberType::class,[
                'attr'=>['min'=>1],
            ])
            ->add('lieu',EntityType::class,
                [
                    'label' => "Lieu : ",
                    'class' => Lieu::class,
                    'required' => true,
                   'choice_label' => function(Lieu $lieu){
                    return $lieu->getNom().'-'.$lieu->getRue().'-'.$lieu->getVille()->getCodePostal();
                   }]
//                // TODO choicelabel: choice label est un array, et si le choice label est un objet lieu
//                    'choice_label' => new Lieu()
            )
            ->add('Ville',EntityType::class,
                [
                    'class' => Ville::class,
                    'label' => function(Ville $ville){
                        return $ville->getNom();
                    }
                ])

//            ->add('etat')
           // ->add('participants')
            //->add('organisateurs')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}
