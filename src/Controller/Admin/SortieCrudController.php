<?php

namespace App\Controller\Admin;

use App\Entity\Etat;
use App\Entity\Sortie;
use Doctrine\DBAL\Types\TimeImmutableType;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TimeField;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class SortieCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Sortie::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [

            IdField::new('id')->hideOnForm(),
            TextField::new('nom'),
            DateTimeField::new('dateHeureDebut'),
            DateTimeField::new('dateLimiteInscription'),
            IntegerField::new('duree'),
            IntegerField::new('nbInscriptionMax'),
            TextField::new('infosSortie'),
            TextField::new("motifAnnulation"),
            AssociationField::new('lieu'),
            AssociationField::new('etat'),
            TextField::new('siteOrganisateur'),
            ];

    }
    public function deleteEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $etatannulee=$entityManager->find(Etat::class,7);
        $entityInstance->setEtat($etatannulee);
        $entityInstance->setmotifAnnulation('annulé par l\'admin');
        $entityManager->persist($entityInstance);
        $entityManager->flush();
    }

}
