<?php

namespace App\Controller\Admin;

use App\Entity\Direction;
use App\Form\DirectionForm;
use App\Form\UploadDirectionForm;
use App\Repository\AffectationRepository;
use App\Repository\DirectionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use PhpOffice\PhpSpreadsheet\IOFactory;

#[Route('/admin/direction', 'app_admin_direction_')]
final class DirectionController extends AbstractController
{
    #[Route('/',name: 'index', methods: ['GET'])]
    public function index(DirectionRepository $directionRepository): Response
    {
        return $this->render('admin/direction/index.html.twig', [
            'directions' => $directionRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'create', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $direction = new Direction();
        $form = $this->createForm(DirectionForm::class, $direction);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($direction);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_direction_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/direction/new.html.twig', [
            'direction' => $direction,
            'form' => $form,
        ]);
    }

    #[Route('/upload', name: 'upload', methods: ['GET', 'POST'])]
    public function upload(Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UploadDirectionForm::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Handle the file upload and processing logic here
            // ...
            $file= $form->get('excel_file')->getData();
            if ($file) {
                // Process the uploaded file (e.g., read data, save to database)
                // This is where you would implement your file processing logic
                $spreadsheet= IOFactory::load($file->getPathname());
                $sheet=$spreadsheet->getActiveSheet();
                $rows = $sheet->toArray();
                foreach ($rows as $index=> $row) {
                    if ($index === 0) {
                        // Skip header row
                        continue;
                    }
                    // Vérification des doublons
                    $existingDirection = $entityManager->getRepository(Direction::class)->findOneBy(['typeDirection' => $row[0], 'nomDirection' => $row[1]]);
                    if ($existingDirection) {
                        $this->addFlash('error', 'La direction ' . $row[1] . ' existe déjà.');
                        continue;
                    }
                    $direction = new Direction();
                    $direction->setTypeDirection(type_direction: $row[0]); // Assuming the first column is the name
                    $direction->setNomDirection($row[1]); // Assuming the first column is the name
                    $direction->setDescription($row[2]); // Assuming the second column is the description
                    // Add other fields as necessary

                    $entityManager->persist($direction);
                }
                $entityManager->flush();


            }

            $this->addFlash('success', 'Fichier importé avec succès.');
            return $this->redirectToRoute('app_admin_direction_index');
        }

        return $this->render('admin/direction/upload.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(Direction $direction, AffectationRepository $affectationRepository): Response
    {
        // Fetching all affectations related to the direction and the statut_affectation
        $affectations = $affectationRepository->findByDirectionStatutAffectation($direction, 'en service');
        return $this->render('admin/direction/show.html.twig', [
            'direction' => $direction,
            'affectations' => $affectations,
        ]);
    }

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'],requirements: ['id' => Requirement::DIGITS])]
    public function edit(Request $request, Direction $direction, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(DirectionForm::class, $direction);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_direction_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/direction/edit.html.twig', [
            'direction' => $direction,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['POST'], requirements: ['id' => Requirement::DIGITS])]
    public function delete(Request $request, Direction $direction, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$direction->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($direction);
            $entityManager->flush();
            $this->addFlash('success', 'La direction a été supprimée avec succès.');
        }
        return $this->redirectToRoute('app_admin_direction_index', [], Response::HTTP_SEE_OTHER);
    }
}
