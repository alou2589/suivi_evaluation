<?php

namespace App\DataFixtures;

use App\Entity\Affectation;
use App\Entity\Agent;
use App\Entity\Direction;
use App\Entity\InfoPerso;
use App\Entity\Poste;
use App\Entity\Service;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Color\Color;
use League\Flysystem\FilesystemOperator;

class PersonnelFixtures extends Fixture
{

    private FilesystemOperator $filesystemOperator;
    public function __construct(FilesystemOperator $sftpStorage)
    {
        $this->filesystemOperator = $sftpStorage;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR'); // pour des données francophones



        // Quelques exemples de localités sénégalaises
        // =============================
        // 4️⃣ INFO_PERSO
        // =============================

        $prenomMasculins = [
            'Mamadou', 'Cheikh', 'Ousmane', 'Ibrahima', 'Serigne', 'Abdoulaye',
            'Moussa', 'Pape', 'Babacar', 'Modou', 'El Hadji', 'Alioune',
            'Tidiane', 'Fallou', 'Bamba', 'Souleymane',
            'Jean', 'Joseph', 'Pierre', 'André', 'Antoine', 'Paul',
            'Jacques', 'Michel', 'Étienne', 'François', 'Laurent', 'Armand',
            'Denis', 'Bernard', 'Christian', 'Marcel'
        ];

        $prenomFeminins = [
            'Aïssatou', 'Mariama', 'Fatou', 'Aminata', 'Sokhna', 'Mame Diarra',
            'Ndeye', 'Adji', 'Coumba', 'Khady', 'Ndèye Arame', 'Rokhaya',
            'Bineta', 'Astou', 'Diarra', 'Dieynaba',
            'Marie', 'Thérèse', 'Bernadette', 'Adèle', 'Madeleine', 'Rose',
            'Hélène', 'Cécile', 'Angélique', 'Joséphine', 'Clarisse', 'Marguerite',
            'Brigitte', 'Lucie', 'Monique', 'Geneviève'
        ];
        $noms = [
            'Ndiaye', 'Diop', 'Fall', 'Sow', 'Ba', 'Kane', 'Cissé', 'Sy',
            'Gaye', 'Faye', 'Sarr', 'Ndour', 'Diouf', 'Diagne', 'Thiam',
            'Barry', 'Niang', 'Gueye', 'Diallo'
        ];

        $communes = [
            'Dakar', 'Guédiawaye', 'Pikine', 'Rufisque', 'Thiès', 'Mbour', 'Tivaouane',
            'Saint-Louis', 'Podor', 'Richard-Toll', 'Louga', 'Kébémer', 'Linguère',
            'Kaolack', 'Nioro du Rip', 'Kaffrine', 'Kolda', 'Vélingara', 'Sédhiou',
            'Ziguinchor', 'Bignona', 'Tambacounda', 'Koumpentoum', 'Bakel',
            'Matam', 'Ranérou', 'Goudiry', 'Fatick', 'Foundiougne', 'Diourbel',
            'Bambey', 'Mbacké', 'Kédougou', 'Saraya'
        ];

        // 🔹 Quelques quartiers connus
        $quartiers = [
            'Medina', 'HLM', 'Fass', 'Ngor', 'Yoff', 'Ouakam', 'Grand Yoff',
            'Plateau', 'Parcelles Assainies', 'Colobane', 'Khar Yalla', 'Hann Bel-Air',
            'Ndiaréme', 'Darou Khoudoss', 'Diamaguène', 'Thiaroye', 'Golf Sud',
            'Liberté 6', 'Cité Keur Gorgui', 'Cité Asecna', 'Cité des Enseignants',
            'Santhiaba', 'Gouye Mouride', 'Tivaouane Peulh', 'Keur Massar'
        ];
        $situations = ['Célibataire', 'Marié(e)', 'Divorcé(e)', 'Veuf(ve)'];
        $sexe=$faker->randomElement(['Homme','Femme']);
        $hierarchies = ['A1', 'A2', 'B1', 'B2', 'B4', 'C1', 'C2','C3','D1','D2','D3'];
        $grades = ['1ère classe', '2ème classe', '3ème classe', '4ème classe'];
        $echelons = ['1er echelon', '2ème echelon'];
        $banques = ['BICIS', 'SGBS', 'BOA', 'CBAO', 'ECOBANK', 'BNDE','CNCAS'];
        $decision_contrats = ['Décision', 'Contrat'];
        $lettresMatricule = range('A', 'Z');
        $status_agent=['Actif', 'Inactif'];
        $cadreStatuaire=['Fonctionnaire','Non Fonctionnaire','Contractuel','Stagiaire'];
        $services = $manager->getRepository(Service::class)->findAll();
        $postes=$manager->getRepository(Poste::class)->findAll();

        // Personnel
        for ($i = 1; $i <= 100; $i++) {
            //$prenom = $faker->randomElement([$prenomMasculins,$prenomFeminins]);
            $nom=$faker->randomElement($noms);
            $prenomMasculinChoisi=$faker->randomElement($prenomMasculins);
            $prenomFemininChoisi=$faker->randomElement($prenomFeminins);
            $prenomChoisi=$faker->randomElement([$prenomMasculinChoisi,$prenomFemininChoisi]);
            $dateNaissance=$faker->dateTimeBetween('-65 years', '-20 years');
            $sexe = in_array($prenomChoisi,$prenomMasculins)?'Homme':'Femme';

            $annee = $dateNaissance->format('y');
            $mois = $dateNaissance->format('m');
            $jour = $dateNaissance->format('d');
            $sexeCode = $faker->randomElement(['Homme','Femme']) === 'Homme' ? '1' : '2';
            $rand = str_pad((string)rand(0, 99999), 5, '0', STR_PAD_LEFT);
            $cin = $sexeCode . $annee . $mois . $jour . $rand;

            $telephone = $faker->randomElement(['77','78','76','70','75']) . $faker->numerify('#######');
            $qr_code=self::generateQrCode($cin,$telephone);
            $info = new InfoPerso();
            $agent = new Agent();
            $affectation= new Affectation();
            $info->setPrenom($prenomChoisi);
            $info->setNom($nom);
            $info->setSexe($sexe);
            $info->setDateNaissance($dateNaissance);
            $info->setLieuNaissance($faker->randomElement($communes));
            $info->setCin($cin);
            $info->setEmail(strtolower("$prenomChoisi.$nom@" . $faker->freeEmailDomain()));
            $info->setTelephone($telephone);
            $info->setSituationMatrimoniale($faker->randomElement($situations));
            $info->setAdresse($faker->randomElement($quartiers));
            $info->setQrCode($qr_code);

            $manager->persist($info);
            $agent->setIdentification($info);
            $agent->setCadreStatuaire($faker->randomElement($cadreStatuaire));
            if($cadreStatuaire==='Contractuel') {
                $matricule= 'C'.$info->getTelephone();
            } elseif($cadreStatuaire === 'Stagiaire') {
                $matricule= 'S'.$info->getTelephone();
            } else{
                $matricule= $faker->numerify('######').$faker->randomElement($lettresMatricule);
            }
            $agent->setMatricule($matricule);
            $agent->setFonction($faker->jobTitle());
            $agent->setHierarchie($faker->randomElement($hierarchies));
            $agent->setGrade($faker->randomElement($grades));
            $agent->setEchelon($faker->randomElement($echelons));
            $agent->setDecisionContrat($faker->randomElement($decision_contrats));
            $agent->setNumeroDecisionContrat('DC-' . $faker->unique()->numerify('####'));
            $agent->setDateRecrutement($faker->dateTimeBetween('-15 years', 'now'));
            $agent->setBanque($faker->randomElement($banques));
            $agent->setNumeroCompte($faker->numerify('221##########'));
            $agent->setStatus($faker->randomElement($status_agent));
            $manager->persist($agent);
            //Affectation
            $affectation->setAgent($agent);
            $affectation->setPoste($faker->randomElement($postes));
            $affectation->setService($faker->randomElement($services));
            $affectation->setDateDebut($faker->dateTimeBetween('-10 years', 'now'));
            $affectation->setDateFin($faker->optional(0.2)->dateTimeBetween('now', '+3 years'));
            $affectation->setStatutAffectation($faker->randomElement(['En poste', 'Muté', 'En congé', 'Suspendu']));
            $manager->persist($affectation);
        }

        $manager->flush();
    }

    public function generateQrCode(string $content, string $nom_qr): string
    {
        $fileName= $nom_qr.uniqid('', true).'.png';
        $tempPath = sys_get_temp_dir() . '/' . $fileName;
        $path = dirname(__DIR__, 2) . '/public/assets/';
        $builder = new Builder(
            writer: new PngWriter(),
            writerOptions: [],
            validateResult: false,
            data: ((string)$content),
            encoding: new Encoding('UTF-8'),
            errorCorrectionLevel: ErrorCorrectionLevel::High,
            size: 400,
            margin: 10,
            roundBlockSizeMode: RoundBlockSizeMode::Margin,
            backgroundColor: new Color(0, 153, 51),
            logoPath:(string) $path.'img/logo.png',
            logoResizeToHeight: 100,
            logoResizeToWidth: 100,
        );
        $result = $builder->build();
        $result->saveToFile($tempPath);
        $stream= fopen($tempPath, 'r');
        $remotePath=(string) 'qr_codes/'.$fileName;
        $this->filesystemOperator->writeStream($remotePath, $stream);
        fclose($stream);
        unlink($tempPath); // Delete the temporary file

        return $remotePath;
    }
}
