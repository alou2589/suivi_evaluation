<?php

namespace App\Controller\Admin;

use App\Entity\Poste;
use App\Form\PosteForm;
use App\Form\UploadFileForm;
use App\Repository\AffectationRepository;
use App\Repository\PosteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use PhpOffice\PhpSpreadsheet\IOFactory;

#[Route('/admin/poste', name:'app_admin_poste_')]
final class PosteController extends AbstractController
{
    #[Route(name: 'index', methods: ['GET'])]
    public function index(PosteRepository $posteRepository): Response
    {
        return $this->render('admin/poste/index.html.twig', [
            'postes' => $posteRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'create', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $poste = new Poste();
        $form = $this->createForm(PosteForm::class, $poste);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($poste);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_poste_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/poste/new.html.twig', [
            'poste' => $poste,
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
                    $existingPoste = $entityManager->getRepository(Poste::class)->findOneBy(['nom_poste' => $row[0]]);
                    if ($existingPoste) {
                        $this->addFlash('error', 'Le poste ' . $row[1] . ' existe déjà.');
                        continue;
                    }
                    $poste = new Poste();
                    $poste->setNomPoste($row[0]); // Assuming the first column is the name
                    $poste->setDescription($row[1]); // Assuming the second column is the description
                    // Add other fields as necessary

                    $entityManager->persist($poste);
                }
                $entityManager->flush();


            }

            $this->addFlash('success', 'Fichier importé avec succès.');
            return $this->redirectToRoute('app_admin_poste_index');
        }

        return $this->render('admin/uploadfiles/upload.html.twig', [
            'form' => $form->createView(),
            'nom_fichier' => 'Poste', // This can be dynamic based on the file type
            'redirectCancelRoute' => 'app_admin_poste_index', // Redirect route after cancellation
        ]);
    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(Poste $poste, AffectationRepository $affectationRepository): Response
    {
        // Fetching all affectations related to the table poste
        $affectations=$affectationRepository->findAffectationByPoste($poste);
        return $this->render('admin/poste/show.html.twig', [
            'poste' => $poste,
            'effectif' => count($affectations),
        ]);
    }

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Poste $poste, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PosteForm::class, $poste);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_poste_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/poste/edit.html.twig', [
            'poste' => $poste,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, Poste $poste, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$poste->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($poste);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_admin_poste_index', [], Response::HTTP_SEE_OTHER);
    }
}
