<?php

namespace App\DataFixtures;

use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Faker\Factory;

class ParcInformatiqueFixtures extends Fixture
{


    public function load(ObjectManager $manager): void
    {
        //$faker = Factory::create('fr_FR'); // pour des données francophones
        $faker=Factory::create('fr-FR');
        $type_matos=['Oridnateur Portable', 'Ordinateur Fixe', 'All In One','Impriante', 'Scanner', 'Autre'];
        $marque_matos=['HP', 'Dell', 'Lenovo','Apple','Fujutsu','Canon', 'Ricoh', 'Lexmark', ''];


    }
}
