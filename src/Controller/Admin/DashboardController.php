<?php

namespace App\Controller\Admin;

use App\Entity\Agent;
use App\Entity\Direction;
use App\Entity\DocumentAdministratif;
use App\Entity\MatosInformatique;
use App\Entity\Service;
use App\Repository\DirectionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;


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
    public function indexRH(): Response
    {
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
