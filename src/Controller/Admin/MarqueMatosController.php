<?php

namespace App\Controller\Admin;

use App\Entity\MarqueMatos;
use App\Form\MarqueMatosType;
use App\Form\UploadFileForm;
use App\Repository\MarqueMatosRepository;
use Doctrine\ORM\EntityManagerInterface;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/marque/matos', 'app_admin_marque_matos_')]
final class MarqueMatosController extends AbstractController
{
    #[Route(name: 'index', methods: ['GET'])]
    public function index(MarqueMatosRepository $marqueMatosRepository): Response
    {
        return $this->render('admin/marque_matos/index.html.twig', [
            'marques' => $marqueMatosRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'create', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $marqueMato = new MarqueMatos();
        $form = $this->createForm(MarqueMatosType::class, $marqueMato);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($marqueMato);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_marque_matos_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/marque_matos/new.html.twig', [
            'marque_mato' => $marqueMato,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(MarqueMatos $marqueMato): Response
    {
        return $this->render('admin/marque_matos/show.html.twig', [
            'marque_mato' => $marqueMato,
        ]);
    }

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, MarqueMatos $marqueMato, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(MarqueMatosType::class, $marqueMato);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_marque_matos_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/marque_matos/edit.html.twig', [
            'marque_mato' => $marqueMato,
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
                    $existingMarque = $entityManager->getRepository(MarqueMatos::class)->findOneBy(['nom_marque' => $row[0]]);
                    if ($existingMarque) {
                        $this->addFlash('error', 'La marque ' . $row[1] . ' existe déjà.');
                        continue;
                    }
                    $marque = new MarqueMatos();
                    $marque->setNomMarque($row[0]); // Assuming the first column is the name
                    $marque->setDescriptionMarque($row[1]); // Assuming the second column is the description
                    // Add other fields as necessary

                    $entityManager->persist($marque);
                }
                $entityManager->flush();


            }

            $this->addFlash('success', 'Fichier importé avec succès.');
            return $this->redirectToRoute('app_admin_marque_matos_index');
        }

        return $this->render('admin/uploadfiles/upload.html.twig', [
            'form' => $form->createView(),
            'nom_fichier' => 'MarqueMatos', // This can be dynamic based on the file type
            'redirectCancelRoute' => 'app_admin_marque_matos_index', // Redirect route after cancellation
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, MarqueMatos $marqueMato, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$marqueMato->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($marqueMato);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_admin_marque_matos_index', [], Response::HTTP_SEE_OTHER);
    }
}
