<?php

namespace App\Controller\Admin;

use App\Entity\InfoPerso;
use App\Form\InfoPersoForm;
use App\Form\UploadCniType;
use App\Repository\InfoPersoRepository;
use App\Service\AesEncryptDecrypt;
use App\Service\QrCodeGenerator;
use App\Form\UploadFileForm;
use App\Service\OcrService;
use App\Service\OpenAIService;
use Doctrine\ORM\EntityManagerInterface;
use League\Flysystem\FilesystemOperator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\File\UploadedFile;

#[Route('/admin/info/perso', name: 'app_admin_info_perso_')]
final class InfoPersoController extends AbstractController
{

    #[Route(name: 'index', methods: ['GET'])]
    public function index(InfoPersoRepository $infoPersoRepository): Response
    {
        return $this->render('admin/info_perso/index.html.twig', [
            'info_persos' => $infoPersoRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'create', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, QrCodeGenerator $qrCodeGenerator, AesEncryptDecrypt $aesEncryptDecrypt, InfoPersoRepository $infoPersoRepository, SluggerInterface $slugger): Response
    {
        $infoPerso = new InfoPerso();
        $qr_code=null;
        $form = $this->createForm(InfoPersoForm::class, $infoPerso);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $infoPerso->setQrCode($qr_code);
            $entityManager->persist($infoPerso);
            $entityManager->flush();

            $qr_code = $qrCodeGenerator->generateQrCode($infoPerso->getCin(), $infoPerso->getId());
            $infoPerso->setQrCode((string)$qr_code);
            $entityManager->persist($infoPerso);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_info_perso_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('admin/info_perso/new.html.twig', [
            'info_perso' => $infoPerso,
            'form' => $form,
        ]);
    }

    #[Route('/upload', name: 'upload', methods: ['GET', 'POST'])]
    public function uploadList(Request $request, EntityManagerInterface $entityManager, QrCodeGenerator $qrCodeGenerator, AesEncryptDecrypt $aesEncryptDecrypt): Response
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
                foreach ($rows as $index => $row) {

                    if ($index === 0) {
                        // Skip header row
                        continue;
                    }
                    // Vérification des doublons
                    $existingInfoPerso = $entityManager->getRepository(InfoPerso::class)->findOneBy(['cin' => $row[5]]);
                    if ($existingInfoPerso) {
                        $this->addFlash('error', 'L\'information personnelle avec le CIN ' . $row[5] . ' existe déjà.');
                        continue;
                    }
                    $dateString= $row[3];
                    if(!empty($dateString)){
                        $dateNaissance= \DateTime::createFromFormat('Y-m-d', $dateString);
                        if($dateNaissance != false){
                            $infoPerso = new InfoPerso();
                            // Assuming the columns in the Excel file match the InfoPerso entity fields
                            $infoPerso->setPrenom($row[0]);
                            $infoPerso->setNom($row[1]);
                            $infoPerso->setSexe($row[2]);
                            $infoPerso->setDateNaissance($dateNaissance);
                            $infoPerso->setLieuNaissance($row[4]);
                            $infoPerso->setCin($row[5]);
                            $infoPerso->setEmail($row[6]);
                            $infoPerso->setTelephone($row[7]);
                            $infoPerso->setSituationMatrimoniale($row[8]);
                            $infoPerso->setAdresse($row[9]);
                            //$entityManager->flush();
                            // Add other fields as necessary
                            $qr_code=$qrCodeGenerator->generateQrCode($infoPerso->getCin(), $infoPerso->getTelephone());
                            $infoPerso->setQrCode((string)$qr_code);
                            $entityManager->persist($infoPerso);
                        } else {
                            throw new \Exception("Format de date invalide : $dateString");
                        }
                    }
                }
                $entityManager->flush();
            }

            $this->addFlash('success', 'Fichier importé avec succès.');
            return $this->redirectToRoute('app_admin_info_perso_index');
        }

        return $this->render('admin/uploadfiles/upload.html.twig', [
            'form' => $form->createView(),
            'nom_fichier' => 'InfoPerso', // This can be dynamic based on the file type
            'redirectCancelRoute' => 'app_admin_info_perso_index', // Redirect route after cancellation
        ]);
    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(InfoPerso $infoPerso, #[Autowire(service: 'sftp.storage')] FilesystemOperator $sftpStroge): Response
    {
        if(!$infoPerso){
            throw $this->createNotFoundException('Personne non identifiée :(');
        }
        $qrCodeFileName= $infoPerso->getQrCode();

        if($sftpStroge->fileExists($qrCodeFileName)){
            $qrCodeImageData= $sftpStroge->read($qrCodeFileName);
            $qrCodeBase64=base64_encode($qrCodeImageData);
        }
        else{
            $qrCodeBase64= null;
        }
        return $this->render('admin/info_perso/show.html.twig', [
            'info_perso' => $infoPerso,
            'qrCodeBase64'=>$qrCodeBase64,
        ]);
    }

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, InfoPerso $infoPerso, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(InfoPersoForm::class, $infoPerso);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_info_perso_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/info_perso/edit.html.twig', [
            'info_perso' => $infoPerso,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, InfoPerso $infoPerso, EntityManagerInterface $entityManager,#[Autowire(service: 'sftp.storage')] FilesystemOperator $sftpStroge): Response
    {
        if(!$infoPerso){
            $this->addFlash('Erreur', 'Info personnelle introuvable :(');
            return $this->redirectToRoute('app_admin_info_perso_index');
        }
        if ($this->isCsrfTokenValid('delete'.$infoPerso->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($infoPerso);
            $entityManager->flush();
        }

        $qrCodePath= $infoPerso->getQrCode();
        if($qrCodePath && $sftpStroge->fileExists($qrCodePath)){
            try{
                $sftpStroge->delete($qrCodePath);
            } catch(\Throwable $e){
                $this->addFlash('Alerte', 'QR Code non supprimé sur le serveur distant: '.$e->getMessage());
            }
        }

        return $this->redirectToRoute('app_admin_info_perso_index', [], Response::HTTP_SEE_OTHER);
    }
}
