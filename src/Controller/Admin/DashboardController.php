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
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;


#[Route('/admin/dashboard', name: 'app_admin_dashboard_')]

final class DashboardController extends AbstractController
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em=$em;
    }


    #[IsGranted("ROLE_RH_ADMIN")]
    #[Route('/rh', name: 'ressources_humaines')]
    public function indexRH( ChartBuilderInterface $chartBuilderInterface): Response
    {
        $chart=$chartBuilderInterface->createChart(Chart::TYPE_BAR);
        $directions= $this->em->getRepository(Direction::class)->findAll();
        foreach ($directions as $direction){
            $direction_names[]=$direction->getNomDirection();
            $direction_homme_counts[]= $this->em->getRepository(Affectation::class)->countSexeByDirection($direction, 'Homme');
            $direction_femme_counts[]= $this->em->getRepository(Affectation::class)->countSexeByDirection($direction, 'Femme');
        }
        $chart->setData([
            'labels'=>$direction_names,
            'datasets'=>[
                [
                    'label'=>'Homme',
                    'backgroundColor'=> '#59d05d',
                    'borderColor'=>'#59d05d',
                    'data'=>[$direction_homme_counts]
                ],
                [
                    'label'=>'Femme',
                    'backgroundColor'=> '#fdaf4b',
                    'borderColor'=>'#fdaf4b)',
                    'data'=>[$direction_femme_counts]
                ],
            ]
        ]);
        $chart->setOptions([
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
                'xAxes'=>[
                    [
                        'stacked'=>true,
                    ],
                ],
                'yAxes'=>[
                    [
                        'stacked'=>true,
                    ],
                ],
            ],
        ]);


        $directions= $this->em->getRepository(Direction::class)->findAll();
        $services= $this->em->getRepository(Service::class)->findAll();
        $personnels= $this->em->getRepository(Agent::class)->findAll();
        $hommes= $this->em->getRepository(Agent::class)->countBySexe('Homme');
        $femmes= $this->em->getRepository(Agent::class)->countBySexe('Femme');
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
            'chart'=>$chart,
            'direction_names'=>$direction_names,
            'direction_homme_counts'=>$direction_homme_counts,
            'direction_femme_counts'=>$direction_femme_counts,
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
