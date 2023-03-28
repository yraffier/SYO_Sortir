<?php

namespace App\DataFixtures;

use App\Factory\SortieFactory;
use App\Factory\UtilisateurFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(
        ObjectManager $manager
    ): void
    {

      Factory::create('fr_FR');
     SortieFactory::createMany(15);


        $manager->flush();
    }
}
