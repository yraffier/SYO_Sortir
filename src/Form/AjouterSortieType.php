<?php

namespace App\Form;

use App\Entity\Sortie;
use App\Entity\Lieu;
use App\Entity\Campus;
use App\Entity\Ville;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AjouterSortieType extends AbstractType
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em= $em;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder
            ->add('nom')
            ->add('dateHeureDebut', DateTimeType::class,
                [
                    'label' => "Date de l'activité : ",
                    'widget' => 'single_text',
                    'html5' => true,
                ])
            ->add('dateLimiteInscription', DateTimeType::class,
                [
                    'label' => "Date limite d'inscription : ",
                    'widget' => 'single_text',
                    'html5' => true
                ])
            ->add('duree', TimeType::class,
                [
                    'label' => "Durée : ",
                    'input' => 'timestamp',
                    'placeholder' => [
                     'hour' => 'Heures', 'minute' => 'Minutes',
                ]
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
                ]);

        $builder->addEventListener(FormEvents::PRE_SET_DATA, array($this, 'onPreSetData'));
        $builder->addEventListener(FormEvents::PRE_SUBMIT, array($this, 'onPreSubmit'));
    }
        protected function addElements(FormInterface $form, Ville $ville= null){
        $form->add('ville',EntityType::class, array(
            'required'=> true,
            'data' => $ville,
            'class'=> Ville::class,
            'placeholder'=> 'Veuilliez choisr une ville...',
            'choice_label'=>'nom'
//            'choices'=> $ville ? $ville->getLieux():[]
        ));

        $lieux = array();
            if($ville){
                $lieuRepo=$this->em->getRepository(Lieu::class);
                $lieux = $lieuRepo->createQueryBuilder("q")
                    ->where("q.ville = :villeid")
                    ->setParameter("villeid", $ville->getId())
                    ->getQuery()
                    ->getResult();
            }
            $form->add('lieu',EntityType::class, array(
                'required'=> true,
                'placeholder'=> 'Veuilliez choisir un lieu...',
                'class'=> Lieu::class,
                'choices'=> $lieux
            ));
    }
    function onPreSubmit(FormEvent $event)
    {
        $form = $event->getForm();
        $data = $event->getData();
        $ville = $this->em->getRepository(Ville::class)->find($data['ville']);
        $this->addElements($form, $ville);
    }
    function onPreSetData(FormEvent $event) {
        $sortie = $event->getData();
        $form = $event->getForm();

        // When you create a new person, the City is always empty
        $ville = $sortie->getVille() ? $sortie->getVille() : null;

        $this->addElements($form, $ville);
    }
//        $builder->get('Ville')->addEventListener(
//            FormEvents::PRE_SUBMIT,
//            function (FormEvent $event) {
//                $form = $event->getForm();
//                $this->addLieuField($form->getParent(),$form->getData());
//            });
//    }
//
//    private function addLieuField(FormInterface $form,?Ville $ville){
////        $form->add('lieu',EntityType::class,[
////            'class'=> Lieu::class,
////            'placeholder'=> 'choix',
////            'choices'=> $ville ? $ville->getLieux():[]
////        ]);
//        $builder= $form->getConfig()->getFormFactory()->createNamedBuilder(
//            'lieu',
//            EntityType::class,
//            null,
//            [
//
//                    'class' => Lieu::class,
//                    'placeholder'=> $ville ? "Selectionnez votre lieu" : 'Selectionnez votre ville',
//                    'label'=>'Lieu : ',
//                    'mapped'=> false,
//                    'required' => false,
//                    'auto_initialize' => false,
//                    'choices' => $ville ? $ville->getLieux():[]
//            ]
//        );
//        $form->add($builder->getForm());
//    }
//
       public function configureOptions(OptionsResolver $resolver): void
        {
            $resolver->setDefaults([
                'data_class' => Sortie::class,
            ]);
        }
}
//function (Lieu $lieu) {
// return $lieu->getNom() . '-' . $lieu->getRue() . '-' . $lieu->getVille()->getCodePostal();


//            ->add('lieu',EntityType::class,[
//                'class'=>Lieu::class,
//                'placeholder'=>'Selectionnez votre Lieu',
//                'mapped'=>false,
//                'required'=>false,
//                'choices'=>[]
//            ]);
// ajouter un evenement on créer un builder

//        $builder->get('Ville')->addEventListener(
//            FormEvents::POST_SUBMIT,
//            function (FormEvent $event) {
//                $form = $event->getForm();
//                $form->getParent()->add('lieu',EntityType::class,[
//                    'class'=>Lieu::class,
//                    'placeholder'=>'Selectionnez votre Lieu',
//                    'mapped'=>false,
//                    'required'=>false,
//                    'choices'=>$form->getData()
//                ]);
//
//            });