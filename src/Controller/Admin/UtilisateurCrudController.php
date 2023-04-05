<?php

namespace App\Controller\Admin;

use App\Entity\Utilisateur;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class UtilisateurCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Utilisateur::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('username'),
            TextField::new('nom'),
            TextField::new('prenom'),
            TextField::new('mail'),
            TextField::new('telephone'),
            ArrayField::new('roles')->hideOnForm(),
            BooleanField::new('administrateur')->renderAsSwitch(false),
            BooleanField::new('actif')->renderAsSwitch(false),
            AssociationField::new('campus'),
            TextField::new('plainPassword'),


        ];
    }

}
