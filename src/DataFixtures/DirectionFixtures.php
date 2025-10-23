<?php

namespace App\DataFixtures;

use App\Entity\Direction;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class DirectionFixtures extends Fixture
{

    public function load(ObjectManager $manager): void
    {
        $directionsData = [
            ['nom_direction' => 'DAGE', 'type' => 'Direction', 'description' => "Direction de l’Administration Générale et de l’Équipement"],
            ['nom_direction' => 'DCI', 'type' => 'Direction', 'description' => "Direction du Commerce Intérieur"],
            ['nom_direction' => 'DCE', 'type' => 'Direction', 'description' => "Direction du Commerce Extérieur"],
            ['nom_direction' => 'DPME', 'type' => 'Direction', 'description' => "Direction de la Promotion de la Micro-Entreprise"],
            ['nom_direction' => 'DSDI', 'type' => 'Direction', 'description' => "Direction des Systèmes et du Développement Industriel"],
            ['nom_direction' => 'DPMI', 'type' => 'Direction', 'description' => "Direction de la Promotion des Mines et Industries"],
            ['nom_direction' => 'DRI', 'type' => 'Direction', 'description' => "Direction des Ressources Internes"],
            ['nom_direction' => 'SG', 'type' => 'Direction', 'description' => "Cellules Techniques rattachées au Secrétariat Général"],
            ['nom_direction' => 'DC', 'type' => 'Direction', 'description' => "Direction du Cabinet du Ministre"],
            ['nom_direction' => 'DC-SE', 'type' => 'Direction', 'description' => "Direction du Cabinet du Secrétaire d’État"],
            ['nom_direction' => 'ADEPME', 'type' => 'Direction', 'description' => "Agence de Développement et d'Encadrement des Petites et Moyennes Entreprises"],
            ['nom_direction' => 'ORSRE', 'type' => 'Direction', 'description' => "Organe de Régulation et Système des Récepissés et d'Entrepôts"],
            ['nom_direction' => 'ASEPEX', 'type' => 'Direction', 'description' => "Agence Sénégalaise de Promotion des Exportations"],
            ['nom_direction' => 'ARM', 'type' => 'Agence', 'description' => "Agence de Régulation des Marchés"],
            ['nom_direction' => 'BMN', 'type' => 'Agence', 'description' => "Bureau de Mise à Niveaux"],
            ['nom_direction' => 'BNSTP-S', 'type' => 'Programme', 'description' => "Bureau National de Sous-traitance et de Partenariat au Sénégal"],
            ['nom_direction' => 'APROSI', 'type' => 'Agence', 'description' => "Agence de Promotion des Sites Industriels"],
            ['nom_direction' => 'ASPIT', 'type' => 'Agence', 'description' => "Agence Sénégalaise pour la Propriété Industrielle et l’Innovation Technologique"],
            ['nom_direction' => 'UNMONCIR', 'type' => 'Programme', 'description' => "Unité Nationale de Mise en Œuvre du Cadre Intégré Renforcé"],
        ];
        // Direction
        foreach ($directionsData as $i => $data) {
            $direction = new Direction();
            $direction->setNomDirection($data['nom_direction']);
            $direction->setTypeDirection($data['type']);
            $direction->setDescription($data['description']);
            $manager->persist($direction);
        }

        $manager->flush();
    }
}
