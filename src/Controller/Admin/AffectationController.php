<?php

namespace App\Controller\Admin;

use App\Entity\Affectation;
use App\Entity\Agent;
use App\Entity\Poste;
use App\Entity\Service;
use App\Form\AffectationForm;
use App\Form\UploadFileForm;
use App\Repository\AffectationRepository;
use Doctrine\ORM\EntityManagerInterface;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/affectation', name:'app_admin_affectation_')]
final class AffectationController extends AbstractController
{
    #[Route(name: 'index', methods: ['GET'])]
    public function index(AffectationRepository $affectationRepository): Response
    {
        return $this->render('admin/affectation/index.html.twig', [
            // Replace 1 with the appropriate $id value as needed
            'affectations' => $affectationRepository->findWithAgentServiceAndPoste(),
        ]);
    }

    #[Route('/new', name: 'create', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $affectation = new Affectation();
        $form = $this->createForm(AffectationForm::class, $affectation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($affectation);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_affectation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/affectation/new.html.twig', [
            'affectation' => $affectation,
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
                    $agent=$entityManager->getRepository(Agent::class)->findOneBy(['matricule'=>$row[1]]);
                    $service=$entityManager->getRepository(Service::class)->findOneBy(['nom_service'=>$row[3]]);
                    $poste=$entityManager->getRepository(Poste::class)->findOneBy(['nom_poste'=>$row[2]]);

                    if (!$agent || !$service || !$poste) {
                        $this->addFlash('error', 'La personne ' . $row[0].' n\'existe pas.');
                        continue;
                    }
                    // Vérification des doublons
                    $existingAffectation = $entityManager->getRepository(Affectation::class)->findOneBy(['agent' => $agent->getId()]);
                    if ($existingAffectation) {
                        $this->addFlash('error', ' Cet agent de matricule ' . $row[1] . ' existe déjà.');
                        continue;
                    }
                    $dateStringDebut= $row[4];
                    if(!empty($dateStringDebut)){
                        $dateDebut= \DateTime::createFromFormat('Y-m-d', $dateStringDebut);
                        if($dateDebut != false){
                            $affectation = new Affectation();
                            // Assuming the columns in the Excel file match the InfoPerso entity fields
                            $affectation->setAgent($agent);
                            $affectation->setPoste($poste);
                            $affectation->setService($service);
                            $affectation->setDateDebut($dateDebut);
                            $affectation->setStatutAffectation($row[5]);
                            //$entityManager->flush();
                            // Add other fields as necessary
                            $entityManager->persist($affectation);
                        } else {
                            throw new \Exception("Format de date invalide : $dateStringDebut");
                        }
                    }
                }
                $entityManager->flush();


            }

            $this->addFlash('success', 'Fichier importé avec succès.');
            return $this->redirectToRoute('app_admin_affectation_index');
        }

        return $this->render('admin/uploadfiles/upload.html.twig', [
            'form' => $form->createView(),
            'nom_fichier' => 'Affectation', // This can be dynamic based on the file type
            'redirectCancelRoute' => 'app_admin_affectation_index', // Redirect route after cancellation
        ]);
    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(Affectation $affectation): Response
    {
        return $this->render('admin/affectation/show.html.twig', [
            'affectation' => $affectation,
        ]);
    }

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Affectation $affectation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AffectationForm::class, $affectation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_affectation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/affectation/edit.html.twig', [
            'affectation' => $affectation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, Affectation $affectation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$affectation->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($affectation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_admin_affectation_index', [], Response::HTTP_SEE_OTHER);
    }
}
