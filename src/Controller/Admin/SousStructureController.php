<?php

namespace App\Controller\Admin;

use App\Entity\Service;
use App\Entity\SousStructure;
use App\Form\SousStructureType;
use App\Form\UploadFileForm;
use App\Repository\SousStructureRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use PhpOffice\PhpSpreadsheet\IOFactory;


#[Route('/admin/sous/structure', 'app_admin_sous_structure_')]
final class SousStructureController extends AbstractController
{
    #[Route(name: 'index', methods: ['GET'])]
    public function index(SousStructureRepository $sousStructureRepository): Response
    {
        return $this->render('admin/sous_structure/index.html.twig', [
            'sous_structures' => $sousStructureRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'create', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $sousStructure = new SousStructure();
        $form = $this->createForm(SousStructureType::class, $sousStructure);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($sousStructure);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_sous_structure_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/sous_structure/new.html.twig', [
            'sous_structure' => $sousStructure,
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
                    $service=$entityManager->getRepository(Service::class)->findOneBy(['nom_service' => $row[1]]);

                    // Vérification des doublons
                    $existingSousStructure = $entityManager->getRepository(SousStructure::class)->findOneBy(['nom_sous_structure' => $row[0], 'service_rattache' => $service]);
                    if ($existingSousStructure) {
                        $this->addFlash('error', 'Le service ' . $row[1] . ' existe déjà.');
                        continue;
                    }
                    $sousStructure = new SousStructure();
                    $sousStructure->setNomSousStructure( $row[0]); // Assuming the first column is the name
                    $sousStructure->setServiceRattache($service);
                    $sousStructure->setDescription($row[2]); // Assuming the second column is the description
                    // Add other fields as necessary

                    $entityManager->persist($sousStructure);
                }
                $entityManager->flush();


            }

            $this->addFlash('success', 'Fichier importé avec succès.');
            return $this->redirectToRoute('app_admin_sous_structure_index');
        }

        return $this->render('admin/uploadfiles/upload.html.twig', [
            'form' => $form->createView(),
            'nom_fichier' => 'Sous Structure', // This can be dynamic based on the file type
            'redirectCancelRoute' => 'app_admin_sous_structure_index', // Redirect route after cancellation
        ]);
    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(SousStructure $sousStructure): Response
    {
        return $this->render('admin/sous_structure/show.html.twig', [
            'sous_structure' => $sousStructure,
        ]);
    }

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, SousStructure $sousStructure, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SousStructureType::class, $sousStructure);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_sous_structure_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/sous_structure/edit.html.twig', [
            'sous_structure' => $sousStructure,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, SousStructure $sousStructure, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$sousStructure->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($sousStructure);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_admin_sous_structure_index', [], Response::HTTP_SEE_OTHER);
    }
}
