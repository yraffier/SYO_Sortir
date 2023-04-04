<?php

namespace App\Controller\Admin;

use App\Entity\Lieu;
use App\Entity\Ville;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class LieuCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Lieu::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
             IdField::new('id')->hideOnForm(),
            AssociationField::new('ville'),
            TextField::new('nom'),
            TextField::new('rue'),
            TextField::new('Latitude'),
            TextField::new('longitude'),



        ];
    }

}
