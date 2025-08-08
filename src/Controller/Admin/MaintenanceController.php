<?php

namespace App\Controller\Admin;

use App\Entity\Maintenance;
use App\Form\MaintenanceType;
use App\Repository\MaintenanceRepository;
use App\Service\MaintenanceService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use League\Flysystem\FilesystemOperator;

#[Route('/admin/maintenance', 'app_admin_maintenance_')]
final class MaintenanceController extends AbstractController
{
    #[Route(name: 'index', methods: ['GET'])]
    public function index(MaintenanceRepository $maintenanceRepository): Response
    {
        return $this->render('admin/maintenance/index.html.twig', [
            'maintenances' => $maintenanceRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'create', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, MaintenanceService $maintenanceService): Response
    {
        $maintenance = new Maintenance();
        $form = $this->createForm(MaintenanceType::class, $maintenance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $ficheFile = $form->get('fiche_maintenance')->getData();
            if ($ficheFile) {
                $fichePath = $maintenanceService->uploadFicheMaintenanceInfo($ficheFile);
                $maintenance->setFicheMaintenance($fichePath);
            }
            $entityManager->persist($maintenance);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_maintenance_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/maintenance/new.html.twig', [
            'maintenance' => $maintenance,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(Maintenance $maintenance): Response
    {
        return $this->render('admin/maintenance/show.html.twig', [
            'maintenance' => $maintenance,
        ]);
    }

    #[Route('/{id}/show_fiche_maintenance', name: 'show_fiche_maintenance', methods: ['GET'])]
    public function showFicheMaintenance(Maintenance $maintenance, MaintenanceService $maintenance_service): Response
    {

        if (!$maintenance) {
            throw $this->createNotFoundException("Document non trouvé.");
        }

        $stream = $maintenance_service->getMaintenanceInfoStream($maintenance->getFicheMaintenance());

        if (!$stream) {
            throw $this->createNotFoundException("Document non trouvé.");
        }

        return new Response(stream_get_contents($stream), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="fiche_maintenance_' . $maintenance->getMateriel()->getSnMatos() . '.pdf"',
        ]);
    }

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Maintenance $maintenance, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(MaintenanceType::class, $maintenance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_maintenance_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/maintenance/edit.html.twig', [
            'maintenance' => $maintenance,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, Maintenance $maintenance, EntityManagerInterface $entityManager, #[Autowire(service: 'sftp.storage')] FilesystemOperator $sftpStorage): Response
    {
        if(!$maintenance){
            $this->addFlash('Erreur', 'Document administratif introuvable :(');
            return $this->redirectToRoute('app_admin_doc_admin_index');
        }
        if ($this->isCsrfTokenValid('delete'.$maintenance->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($maintenance);
            $entityManager->flush();
        }

        $fichePath= $maintenance->getFicheMaintenance();
        if($fichePath && $sftpStorage->fileExists($fichePath)){
            try{
                $sftpStorage->delete($fichePath);
            } catch(\Throwable $e){
                $this->addFlash('Alerte', 'Document non supprimé sur le serveur distant: '.$e->getMessage());
            }
        }

        return $this->redirectToRoute('app_admin_doc_admin_index', [], Response::HTTP_SEE_OTHER);
    }
}
