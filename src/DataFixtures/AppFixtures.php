<?php

namespace App\DataFixtures;

use App\Factory\CampusFactory;
use App\Factory\EtatFactory;
use App\Factory\LieuFactory;
use App\Factory\SortieFactory;
use App\Factory\UtilisateurFactory;
use App\Factory\VilleFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        SortieFactory::createMany(15);
//        VilleFactory::createMany(15);
//        EtatFactory::createMany(15);
//        LieuFactory::createMany(15);
//        CampusFactory::createMany(15);
//        UtilisateurFactory::createMany(15);


        $manager->flush();
    }
}
