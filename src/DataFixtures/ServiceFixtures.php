<?php

namespace App\DataFixtures;

use App\Entity\Direction;
use App\Entity\Service;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ServiceFixtures extends Fixture
{


    public function load(ObjectManager $manager): void
    {

        $servicesData = [
            // Services du SG
            ['nom' => 'BAD', 'type' => 'Service', 'direction' => 'SG', 'description' => 'Bureau des Archives et de la Documentation'],
            ['nom' => 'CI', 'type' => 'Cellule', 'direction' => 'SG', 'description' => ' Cellule Informatique'],
            ['nom' => 'CPM', 'type' => 'Cellule', 'direction' => 'SG', 'description' => 'Cellule de Passation des Marchés'],
            ['nom' => 'CAJ', 'type' => 'Cellule', 'direction' => 'SG', 'description' => 'Cellule des Affaires Juridiques'],
            ['nom' => 'CGE', 'type' => 'Cellule', 'direction' => 'SG', 'description' => 'Cellule Genre et Équité'],
            ['nom' => 'CEPSE', 'type' => 'Cellule', 'direction' => 'SG', 'description' => 'Cellule Étude, Planification et Suivi-Évaluation'],
            ['nom' => 'BCC', 'type' => 'Bureau', 'direction' => 'SG', 'description' => 'Bureau du Courrier Commun'],
            ['nom' => 'CCCG', 'type' => 'Cellule', 'direction' => 'SG', 'description' => 'Cellule de Coordination du Contrôle de Gestion'],

            // Services du Cabinet du Ministre (DC)
            ['nom' => 'CTs-DC', 'type' => 'Service', 'direction' => 'DC', 'description' => 'Conseillers Techniques du Cabinet du Ministre'],
            ['nom' => 'CISP', 'type' => 'Cellule', 'direction' => 'DC', 'description' => 'Cellule d’Intermédiation avec le Secteur Privé'],
            ['nom' => 'CCOM', 'type' => 'Cellule', 'direction' => 'DC', 'description' => 'Cellule de Communication (CCOM)'],
            ['nom' => 'CRADES', 'type' => 'Centre', 'direction' => 'DC', 'description' => 'Centre de Recherches, d’Analyses des Échanges et des Statistiques'],
            ['nom' => 'II', 'type' => 'Service', 'direction' => 'DC', 'description' => 'Inspection Interne'],

            // Cabinet du Secrétaire d’État
            ['nom' => 'CTs-DC-SE', 'type' => 'Service', 'direction' => 'DC-SE', 'description' => 'Conseillers Techniques du Cabinet du Secrétaire d’État'],

            //Services de la DAGE
            ['nom' => 'DRH', 'type' => 'Division', 'direction' => 'DAGE', 'description' => 'Division des Ressources Humaines'],
            ['nom' => 'DCM', 'type' => 'Division', 'direction' => 'DAGE', 'description' => 'Division de la Comptabilité des Matières'],
            ['nom' => 'BGF', 'type' => 'Bureau', 'direction' => 'DAGE', 'description' => 'Bureau de Gestion Financière'],
        ];
        //Service
        foreach ($servicesData as $data) {
            $service = new Service();
            $service->setNomService($data['nom']);
            $service->setTypeService($data['type']);
            //$service->setStructureRattachee($this->getReference(Direction::class, $data['direction']));
            $service->setStructureRattachee($manager->getRepository(Direction::class)->findOneBy(['nom_direction' => $data['direction']]));
            $service->setDescription($data['description']);
            $manager->persist($service);
        }
        $manager->flush();
    }
}
