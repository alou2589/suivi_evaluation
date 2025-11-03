<?php

namespace App\DataFixtures;

use App\Entity\Direction;
use App\Entity\Poste;
use App\Entity\Service;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

    class StructureFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // === DIRECTIONS PRINCIPALES ===
        $directionsData = [
            ['nom' => 'DAGE', 'type' => 'Centrale', 'description' => "Direction de l’Administration Générale et de l’Équipement"],
            ['nom' => 'DCI', 'type' => 'Centrale', 'description' => "Direction du Commerce Intérieur"],
            ['nom' => 'DCE', 'type' => 'Centrale', 'description' => "Direction du Commerce Extérieur"],
            ['nom' => 'DPME', 'type' => 'Centrale', 'description' => "Direction de la Promotion de la Micro-Entreprise"],
            ['nom' => 'DSDI', 'type' => 'Technique', 'description' => "Direction des Systèmes et du Développement Industriel"],
            ['nom' => 'DPMI', 'type' => 'Technique', 'description' => "Direction de la Promotion des Mines et Industries"],
            ['nom' => 'DRI', 'type' => 'Centrale', 'description' => "Direction des Ressources Internes"],
            ['nom' => 'CTs-SG', 'type' => 'Technique', 'description' => "Cellules Techniques rattachées au Secrétariat Général"],
            ['nom' => 'DC', 'type' => 'Cabinet', 'description' => "Direction du Cabinet du Ministre"],
            ['nom' => 'DC-SE', 'type' => 'Cabinet', 'description' => "Direction du Cabinet du Secrétaire d’État"],
        ];

        $directions = [];
        foreach ($directionsData as $data) {
            $direction = new Direction();
            $direction->setNomDirection($data['nom']);
            $direction->setTypeDirection($data['type']);
            $direction->setDescription($data['description']);
            $manager->persist($direction);
            $directions[$data['nom']] = $direction;
        }



        // == POSTES
        $postesDatas=[
            ['nom'=>'Ministre', 'description'=>'Ministre'],
            ['nom'=>'DC', 'description'=>'Directeur de Cabinet'],
            ['nom'=>'SG', 'description'=>'Secrétaire Général'],
            ['nom'=>'CC', 'description'=>'Chef de Cabinet'],
            ['nom'=>'CT-CAB', 'description'=>'Conseillers Techniques du Cabinet'],
            ['nom'=>'CT-SG', 'description'=>'Conseillers Techniques du SG'],
            ['nom'=>'SP', 'description'=>'Secrétaire Particulier'],
            ['nom'=>'RS', 'description'=>'Responsable de Service/Cellule'],
            ['nom'=>'CD', 'description'=>'Chef de Division'],
            ['nom'=>'CB', 'description'=>'Chef de Bureau'],
            ['nom'=>'AG', 'description'=>'Agent'],
            ['nom'=>'TECH', 'description'=>'Technicien'],
            ['nom'=>'STAG', 'description'=>'Stagiaire'],
            ['nom'=>'CONT', 'description'=>'Contractuel'],
            ['nom'=>'PREST', 'description'=>'Prestataire'],

        ];



        foreach ($postesDatas as $data) {
            $poste = new Poste();
            $poste->setNomPoste($data['nom']);
            $poste->setDescription($data['description']);
            $manager->persist($poste);
        }

        $manager->flush();
    }
}
