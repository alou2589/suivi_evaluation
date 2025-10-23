<?php

namespace App\Controller\Admin;

use App\Entity\Affectation;
use App\Entity\Agent;
use App\Entity\Direction;
use App\Entity\DocumentAdministratif;
use App\Entity\InfoPerso;
use App\Entity\MatosInformatique;
use App\Entity\Service;
use App\Repository\DirectionRepository;
use Doctrine\ORM\EntityManagerInterface;
use PhpOffice\PhpSpreadsheet\Chart\GridLines;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;



#[Route('/admin/dashboard', name: 'app_admin_dashboard_')]

final class DashboardController extends AbstractController
{

    public function getRandomColor($nb_colors)
    {
        $colors = [];
        for ($i = 0; $i < $nb_colors; $i++) {
            # code...
            $r = rand(0, 255);
            $g = rand(0, 255);
            $b = rand(0, 255);
            $color = 'rgb(' . $r . ',' . $g . ',' . $b . ')';
            array_push($colors, $color);
        }
        return $colors;
    }
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em=$em;
    }


    #[IsGranted("ROLE_RH_ADMIN")]
    #[Route('/rh', name: 'ressources_humaines')]
    public function indexRH( ChartBuilderInterface $chartBuilderInterface): Response
    {
        $chartSexeByDirection=$chartBuilderInterface->createChart(Chart::TYPE_BAR);
        $chartByDirection=$chartBuilderInterface->createChart(Chart::TYPE_BAR);
        $chartByAgeRange=$chartBuilderInterface->createChart(Chart::TYPE_PIE);
        $chartByHierarchie= $chartBuilderInterface->createChart(Chart::TYPE_BAR);
        $chartEvolutionAgent= $chartBuilderInterface->createChart(Chart::TYPE_LINE);
        //$chartByService= $chartBuilderInterface->createChart(Chart::TYPE_DOUGHNUT);

        $personnels= $this->em->getRepository(Affectation::class)->findAll();
        $directions= $this->em->getRepository(Direction::class)->findAll();
        $services= $this->em->getRepository(Service::class)->findAll();
        $moins25ans= $this->em->getRepository(Affectation::class)->countByAgeRange(0, 25);
        $de25a35ans= $this->em->getRepository(Affectation::class)->countByAgeRange(25, 35);
        $de36a45ans= $this->em->getRepository(Affectation::class)->countByAgeRange(36, 45);
        $de46a55ans= $this->em->getRepository(Affectation::class)->countByAgeRange(46, 55);
        $plus55ans= $this->em->getRepository(Affectation::class)->countByAgeRange(55, 100);

        //Évolution des agents au fil des années
        $agents=$this->em->getRepository(Agent::class)->evolutionAgent();
        $agentHommes=$this->em->getRepository(Agent::class)->evolutionAgentBySexe('Homme');
        $agentFemmes=$this->em->getRepository(Agent::class)->evolutionAgentBySexe('Femme');
        foreach($agents as $agent){
            $date_records[]=$agent['date_record'];
            $nb_recrus[]=$agent['nb_recrus'];
        }
        foreach($agentHommes as $agent){
            $nb_recrus_hommes[]=$agent['nb_recrus'];
        }
        foreach($agentFemmes as $agent){
            $nb_recrus_femmes[]=$agent['nb_recrus'];
        }

        $hierarchieA=$this->em->getRepository(Affectation::class)->countByHierarchie('A');
        $hierarchieB=$this->em->getRepository(Affectation::class)->countByHierarchie('B');
        $hierarchieC=$this->em->getRepository(Affectation::class)->countByHierarchie('C');
        $hierarchieD=$this->em->getRepository(Affectation::class)->countByHierarchie('D');
        $nonClasse=$this->em->getRepository(Affectation::class)->countByHierarchie('NI');
        $direction_names=[];
        foreach ($directions as $direction){
            $direction_names[]=$direction->getNomDirection();
            $direction_homme_counts[]= $this->em->getRepository(Affectation::class)->countSexeByDirection($direction, 'Homme');
            $direction_femme_counts[]= $this->em->getRepository(Affectation::class)->countSexeByDirection($direction, 'Femme');
            $direction_counts[]= $this->em->getRepository(Affectation::class)->countByDirection($direction);
        }
        //foreach( $services as $service){
        //    $service_names[]=$service->getNomService();
        //    $service_counts[]= $this->em->getRepository(Affectation::class)->countByService($service);
        //}

        //$ageAverage= $this->em->getRepository(Affectation::class)->ageAverage();
        $totalAge=0;
        $countAge=0;
        $totalAverageAge=0;
        foreach($personnels as $personnel){
            $totalAge+= $this->em->getRepository(Affectation::class)->getAgeByAffectation($personnel);
            $countAge++;
            $totalAverageAge=$totalAge/$countAge;
        }

        $chartByAgeRange->setData([
            'labels'=>['< 25 ans', '25-35 ans', '36-45 ans', '46-55 ans', '> 55 ans'],
            'datasets'=>[
                [
                    'data'=>[$moins25ans, $de25a35ans, $de36a45ans, $de46a55ans, $plus55ans],
                    'backgroundColor'=> self::getRandomColor(5),
                    'borderColor'=> '#FFFF',
                    'borderWidth'=> 1
                ]
            ]
        ]);
        $chartByHierarchie->setData([
            'labels'=>['A', 'B', 'C', 'D', 'NI'],
            'datasets'=>[
                [
                    'data'=>[$hierarchieA, $hierarchieB, $hierarchieC, $hierarchieD],
                    'backgroundColor'=> self::getRandomColor(4),
                    'borderColor'=> '#FFFF',
                    'borderWidth'=> 1
                ]
            ]
        ]);
        $chartByHierarchie->setOptions([
            'responsive'=>true,
            'maintainAspectRatio'=>false,
            'plugins'=>[
                'legend'=>[
                    'position'=>'right'
                ],
                'title'=>[
                    'display'=>false,
                    'text'=>'Répartition du personnel par tranche d\'âge'
                ],
            ]
        ]);
        $chartByDirection->setData([
            'labels'=>$direction_names,
            'datasets'=>[
                [
                    'label'=>'Personnel',
                    'data'=>array_values($direction_counts??null),
                    'backgroundColor'=> self::getRandomColor(count($direction_counts)),
                    'borderColor'=> '#FFFF',
                    'borderWidth'=> 1
                ]
            ]
        ]);

        $chartByAgeRange->setOptions([
            'responsive'=>true,
            'maintainAspectRatio'=>false,
            'plugins'=>[
                'legend'=>[
                    'position'=>'right'
                ],
                'title'=>[
                    'display'=>false,
                    'text'=>'Répartition du personnel par tranche d\'âge'
                ],
            ]
        ]);
        $chartByDirection->setOptions([
            'responsive'=>true,
            'maintainAspectRatio'=>false,
            //'indexAxis'=>'y',
            'plugins'=>[
                'legend'=>[
                    'position'=>'right'
                ],
                'title'=>[
                    'display'=>false,
                ],
                'tooltips'=>[
                    'mode'=>'index',
                    'intersect'=>false,

                ],
            ],
            'scales'=>[
                'x'=>[
                    [
                        'beginAtZero'=>true,
                        'max'=>100,
                        'grid'=>[
                            'display'=>false,
                        ],
                        'ticks'=>[
                            'stepSize'=>10,
                        ]
                    ]
                ],
                'y'=>[
                    [
                        'grid'=>[
                            'display'=>false,
                        ],
                    ],
                ],
            ]

        ]);

        $chartSexeByDirection->setData([
            'labels'=>$direction_names,
            'datasets'=>[
                [
                    'label'=>'Homme',
                    'backgroundColor'=> '#59d05d',
                    'borderColor'=>'#59d05d',
                    'data'=>array_values($direction_homme_counts)
                ],
                [
                    'label'=>'Femme',
                    'backgroundColor'=> '#fdaf4b',
                    'borderColor'=>'#fdaf4b',
                    'data'=>array_values($direction_femme_counts)
                ],
            ]
        ]);
        $chartSexeByDirection->setOptions([
            'responsive'=>true,
            'maintainAspectRatio'=>false,
            'legend'=>[
                'position'=>'bottom'
            ],
            'title'=>[
                'display'=>true,
                'text'=>'Répartition du personnel par sexe'
            ],
            'tooltips'=>[
                'mode'=>'index',
                'intersect'=>false,
            ],
            'scales'=>[
                'x'=>[
                    [
                        'stacked'=>true,
                        'grid'=>[
                            'display'=>false,
                        ],
                    ]
                ],
                'y'=>[
                    [
                        'stacked'=>true,
                        'beginAtZero'=>true,
                        'grid'=>[
                            'display'=>false,
                        ],
                    ],
                ],
            ]
        ]);

        $chartEvolutionAgent->setData([
            'labels'=>$date_records,
            'datasets'=>[
                [
                    'label'=>'Personnel',
                    'backgroundColor'=> 'transparent',
                    'borderColor'=>'#074814ff',
                    'pointBorderColor'=>"#FFFF",
                    'pointBorderWidth'=>2,
                    'pointHoverRadius'=>4,
                    'pointHoverBorderWidth'=>1,
                    'pointRadius'=>4,
                    'fill'=>true,
                    'border_width'=>2,
                    'data'=>array_values($nb_recrus)
                ],
                [
                    'label'=>'Homme',
                    'backgroundColor'=> 'transparent',
                    'borderColor'=>'#5459dfff',
                    'pointBorderColor'=>"#FFFF",
                    'pointBorderWidth'=>2,
                    'pointHoverRadius'=>4,
                    'pointHoverBorderWidth'=>1,
                    'pointRadius'=>4,
                    'fill'=>true,
                    'border_width'=>2,
                    'data'=>array_values($nb_recrus_hommes)
                ],
                [
                    'label'=>'Femme',
                    'backgroundColor'=> 'transparent',
                    'borderColor'=>'#d27ac2ff',
                    'pointBorderColor'=>"#FFFF",
                    'pointBorderWidth'=>2,
                    'pointHoverRadius'=>4,
                    'pointHoverBorderWidth'=>1,
                    'pointRadius'=>4,
                    'fill'=>true,
                    'border_width'=>2,
                    'data'=>array_values($nb_recrus_femmes)
                ],
            ]
        ]);
        $chartEvolutionAgent->setOptions([
            'responsive'=>true,
            'maintainAspectRatio'=>false,
            'legend'=>[
                'position'=>'bottom'
            ],
            'title'=>[
                'display'=>false,
            ],
            'tooltips'=>[
                'bodySpacing'=>4,
                'mode'=>"nearest",
                'intersect'=>0,
                'position'=>"nearest",
                'xPadding'=>10,
                'yPadding'=>10,
                'caretPadding'=>10,
            ],
            'layout'=>[
                'padding'=>[
                    'left'=>15,
                    'right'=>15,
                    'top'=>15,
                    'bottom'=>15
                ]
            ]
        ]);


        $hommes= $this->em->getRepository(Affectation::class)->countBySexe('Homme');
        $femmes= $this->em->getRepository(Affectation::class)->countBySexe('Femme');
        $nbPDF= $this->em->getRepository(DocumentAdministratif::class)->countByDocumentExtension('pdf');
        $nbDOCX= $this->em->getRepository(DocumentAdministratif::class)->countByDocumentExtension('docx');
        $nbTypeDirection= count($this->em->getRepository(Direction::class)->findBy(['type_direction' => 'Direction']));
        $nbTypeAgence= count($this->em->getRepository(Direction::class)->findBy(['type_direction' => 'Agence']));
        $nbTypeProjets= count($this->em->getRepository(Direction::class)->findBy(['type_direction' => 'Projets']));
        $nbTypeServices= count($this->em->getRepository(Service::class)->findBy(['type_service' => 'Service']));
        $nbTypeCellules= count($this->em->getRepository(Service::class)->findBy(['type_service' => 'Cellule']));
        $docs= $this->em->getRepository(DocumentAdministratif::class)->findAll();
        return $this->render('admin/dashboard/index_rh.html.twig', [
            'dashboard_title' => 'Ressources Humaines',
            'nbDirections'=>count($directions),
            'nbTypeDirection'=>$nbTypeDirection,
            'nbTypeAgence'=>$nbTypeAgence,
            'nbTypeProjets'=>$nbTypeProjets,
            'nbTypeServices'=>$nbTypeServices,
            'nbTypeCellules'=>$nbTypeCellules,
            'nbServices'=>count($services),
            'nbAgents'=>count($personnels),
            'nbHommes'=>$hommes,
            'nbFemmes'=>$femmes,
            'nbDocs'=>count($docs),
            'nbPDF'=>$nbPDF,
            'nbDOCX'=>$nbDOCX,
            'chartSexeByDirection'=>$chartSexeByDirection,
            'chartByDirection'=>$chartByDirection,
            'chartByAgeRange'=>$chartByAgeRange,
            'chartByHierarchie'=>$chartByHierarchie,
            'chartEvolutionAgent'=>$chartEvolutionAgent,
            'date_records'=>$date_records,
            'nb_recrus'=>$nb_recrus,
            'direction_names'=>$direction_names,
            'direction_homme_counts'=>$direction_homme_counts,
            'direction_femme_counts'=>$direction_femme_counts,
            'totalAverageAge'=>(int) round($totalAverageAge),
            'totalAge'=>$totalAge,
            'countAge'=>$countAge,
            'fonctionnaires'=> $this->em->getRepository(Affectation::class)->countByCadreStatutaire('Fonctionnaire'),
            'nonfonctionnaires'=> $this->em->getRepository(Affectation::class)->countByCadreStatutaire('Non Fonctionnaire'),
            'contractuels'=> $this->em->getRepository(Affectation::class)->countByCadreStatutaire('Contractuel'),
            'stagiaires'=> $this->em->getRepository(Affectation::class)->countByCadreStatutaire('Stagiaire'),
        ]);
    }

    #[IsGranted("ROLE_INFO_ADMIN")]
    #[Route('/informatique', 'informatique')]
    public function indexInformatique(): Response
    {
        $laptops= $this->em->getRepository(MatosInformatique::class)->findBy(['type_matos'=>"ordinateur portable"]);
        $desktops= $this->em->getRepository(MatosInformatique::class)->findBy(['type_matos'=>"ordinateur fixe"]);
        $printers= $this->em->getRepository(MatosInformatique::class)->findBy(['type_matos'=>"imprimante"]);
        $scanners= $this->em->getRepository(MatosInformatique::class)->findBy(['type_matos'=>"scanner"]);
        $others= $this->em->getRepository(MatosInformatique::class)->findBy(['type_matos'=>"autre"]);
        return $this->render('admin/dashboard/index_informatique.html.twig',[
            'dashboard_title'=> 'Informatique',
            'nbLaptops'=>count($laptops),
            'nbDesktops'=>count($desktops),
            'nbPrinters'=>count($printers),
            'nbAllOthers'=>count($scanners)+ count($others),

        ]);
    }

    #[IsGranted("ROLE_CEPSE_ADMIN")]
    #[Route('/cepse', 'cepse')]
    public function indexCEPSE(): Response
    {
        return $this->render('admin/dashboard/index_cepse.html.twig',[
            'dashboard_title'=> 'CEPSE'
        ]);
    }
}
