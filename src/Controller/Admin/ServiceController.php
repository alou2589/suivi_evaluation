<?php

namespace App\Controller\Admin;

use App\Entity\Service;
use App\Entity\Direction;
use App\Form\ServiceForm;
use App\Form\UploadFileForm;
use App\Repository\AffectationRepository;
use App\Repository\ServiceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use PhpOffice\PhpSpreadsheet\IOFactory;

#[Route('/admin/service', 'app_admin_service_')]
final class ServiceController extends AbstractController
{
    #[Route(name: 'index', methods: ['GET'])]
    public function index(ServiceRepository $serviceRepository): Response
    {
        return $this->render('admin/service/index.html.twig', [
            'services' => $serviceRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'create', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $service = new Service();
        $form = $this->createForm(ServiceForm::class, $service);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($service);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_service_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/service/new.html.twig', [
            'service' => $service,
            'form' => $form,
        ]);
    }

    #[Route('/upload', name: 'upload', methods: ['GET', 'POST'])]
    public function upload(Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UploadFileForm::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Handle the file upload and processing logic here
            // ...
            $file= $form->get('excel_file')->getData();
            if ($file) {
                // Process the uploaded file (e.g., read data, save to database)
                $spreadsheet= IOFactory::load($file->getPathname());
                $sheet=$spreadsheet->getActiveSheet();
                $rows = $sheet->toArray();
                foreach ($rows as $index=> $row) {
                    if ($index === 0) {
                        // Skip header row
                        continue;
                    }
                    $direction=$entityManager->getRepository(Direction::class)->findOneBy(['nom_direction' => $row[2]]);
                    
                    // Vérification des doublons
                    $existingService = $entityManager->getRepository(Service::class)->findOneBy(['type_service' => $row[0],'nom_service' => $row[1], 'structure_rattachee' => $direction]);
                    if ($existingService) {
                        $this->addFlash('error', 'Le service ' . $row[1] . ' existe déjà.');
                        continue;
                    }
                    $service = new Service();
                    $service->setTypeService( $row[0]); // Assuming the first column is the name
                    $service->setNomService($row[1]); // Assuming the first column is the name
                    $service->setStructureRattachee($direction);
                    $service->setDescription($row[3]); // Assuming the second column is the description
                    // Add other fields as necessary

                    $entityManager->persist($service);
                }
                $entityManager->flush();


            }

            $this->addFlash('success', 'Fichier importé avec succès.');
            return $this->redirectToRoute('app_admin_service_index');
        }

        return $this->render('admin/uploadfiles/upload.html.twig', [
            'form' => $form->createView(),
            'nom_fichier' => 'Service', // This can be dynamic based on the file type
            'redirectCancelRoute' => 'app_admin_service_index', // Redirect route after cancellation
        ]);
    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(Service $service, AffectationRepository $affectationRepository): Response
    {
        // Fetching all affectations related to the service and statut_service
        $affectations = $affectationRepository->findBy(['service' => $service, 'statut_affectation' => 'en service']);
        return $this->render('admin/service/show.html.twig', [
            'service' => $service,
            'affectations' => $affectations,
        ]);
    }

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Service $service, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ServiceForm::class, $service);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_service_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/service/edit.html.twig', [
            'service' => $service,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, Service $service, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$service->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($service);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_admin_service_index', [], Response::HTTP_SEE_OTHER);
    }
}
