<?php

namespace App\DataFixtures;

use App\Entity\Direction;
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

        // === SERVICES SELON ORGANIGRAMME ===
        $servicesData = [
            // Services du SG
            ['nom' => 'Bureau des Archives et de la Documentation', 'type' => 'Service', 'direction' => 'SG', 'description' => 'Gestion et conservation des documents administratifs'],
            ['nom' => 'Cellule Informatique', 'type' => 'Cellule', 'direction' => 'DSDI', 'description' => 'Maintenance, développement et gestion des systèmes informatiques'],
            ['nom' => 'Cellule de Passation des Marchés', 'type' => 'Cellule', 'direction' => 'DAGE', 'description' => 'Procédures de passation des marchés publics'],
            ['nom' => 'Cellule des Affaires Juridiques', 'type' => 'Cellule', 'direction' => 'SG', 'description' => 'Appui juridique et conformité légale'],
            ['nom' => 'Cellule Genre et Équité', 'type' => 'Cellule', 'direction' => 'SG', 'description' => 'Promotion du genre et de l’équité au travail'],
            ['nom' => 'Cellule Étude, Planification et Suivi-Évaluation', 'type' => 'Cellule', 'direction' => 'SG', 'description' => 'Planification stratégique et évaluation des programmes'],
            ['nom' => 'Bureau du Courrier Commun', 'type' => 'Bureau', 'direction' => 'DAGE', 'description' => 'Gestion du courrier entrant et sortant'],
            ['nom' => 'Cellule de Coordination du Contrôle de Gestion', 'type' => 'Cellule', 'direction' => 'SG', 'description' => 'Suivi du contrôle de gestion'],

            // Services du Cabinet du Ministre (DC)
            ['nom' => 'Cabinet du Ministre', 'type' => 'Service', 'direction' => 'DC', 'description' => 'Appui direct au Ministre (CC, CTs, HCC)'],
            ['nom' => 'Cellule d’Intermédiation avec le Secteur Privé', 'type' => 'Cellule', 'direction' => 'DC', 'description' => 'Dialogue et partenariat public-privé'],
            ['nom' => 'Cellule de Communication (CCOM)', 'type' => 'Cellule', 'direction' => 'DC', 'description' => 'Communication institutionnelle et relations publiques'],
            ['nom' => 'Centre de Recherches, d’Analyses des Échanges et des Statistiques (CERAES)', 'type' => 'Centre', 'direction' => 'DC', 'description' => 'Analyse économique et statistique'],
            ['nom' => 'Inspection Interne', 'type' => 'Service', 'direction' => 'DC', 'description' => 'Contrôle interne et audit organisationnel'],

            // Cabinet du Secrétaire d’État
            ['nom' => 'Cabinet du Secrétaire d’État', 'type' => 'Service', 'direction' => 'DC-SE', 'description' => 'Coordination des activités du Secrétaire d’État'],
        ];

        // == POSTES
        $postesDatas=[
            ['nom'=>'Ministre', 'description'=>'Ministre'],
            ['nom'=>'DC', 'description'=>'Directeur de Cabinet'],
            ['nom'=>'SG', 'description'=>'Secrétaire Général'],
            ['nom'=>'CC', 'description'=>'Chef de Cabinet'],
            ['nom'=>'CT-CAB', 'description'=>'Conseillers Techniques du Cabinet'],
            ['nom'=>'CT-SG', 'description'=>'Conseillers Techniques du SG'],
            ['nom'=>'SP', 'description'=>'Secrétaire Particulier'],
            ['nom'=>'Ministre', 'description'=>'Ministre'],
            ['nom'=>'Ministre', 'description'=>'Ministre'],
        ];

        foreach ($servicesData as $data) {
            $service = new Service();
            $service->setNomService($data['nom']);
            $service->setTypeService($data['type']);
            $service->setStructureRattachee($directions[$data['direction']] ?? null);
            $service->setDescription($data['description']);
            $manager->persist($service);
        }

        $manager->flush();
    }
}
