<?php

namespace App\DataFixtures;

use App\Entity\Poste;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class PosteFixtures extends Fixture
{


    public function load(ObjectManager $manager): void
    {
        $posteDatas=[
            ['sigle' => 'MIN',  'description' => 'Ministre'],
            ['sigle' => 'SG',   'description' => 'Secrétaire Général(e)'],
            ['sigle' => 'DC',   'description' => 'Directeur de Cabinet'],
            ['sigle' => 'DIR',  'description' => 'Directeur / Directrice'],
            ['sigle' => 'CC',   'description' => 'Chef de Cabinet'],
            ['sigle' => 'AC',   'description' => 'Attaché de Cabinet'],
            ['sigle' => 'CP',   'description' => 'Chef de Protocole'],
            ['sigle' => 'CD',   'description' => 'Chef de Division'],
            ['sigle' => 'RS',   'description' => 'Responsable de Service'],
            ['sigle' => 'SP',   'description' => 'Secrétaire Particulier'],
            ['sigle' => 'CM',  'description' => 'Comptabilité des Matières'],
            ['sigle' => 'GES',   'description' => 'Gestionnaire'],
            ['sigle' => 'TEC',  'description' => 'Technicien / Technicienne'],
            ['sigle' => 'CHAU', 'description' => 'Chauffeur'],
            ['sigle' => 'DIG',  'description' => 'Digitaliste'],
            ['sigle' => 'JOURN',  'description' => 'Journaliste'],
            ['sigle' => 'AG',  'description' => 'Agent'],
        ];
        //Poste
        foreach ($posteDatas as $posteData) {
            $poste = new Poste();
            $poste->setNomPoste($posteData['sigle']);
            $poste->setDescription($posteData['description']);
            $manager->persist($poste);
        }

        $manager->flush();
    }
}
