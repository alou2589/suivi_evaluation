<?php

namespace App\Controller\Admin;

use App\Entity\MatosInformatique;
use App\Form\MatosInformatiqueType;
use App\Form\UploadFileForm;
use App\Repository\MatosInformatiqueRepository;
use App\Service\SpecificationsMatosInformatique;
use Doctrine\ORM\EntityManagerInterface;
use League\Flysystem\FilesystemOperator;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Smalot\PdfParser\Parser;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\DependencyInjection\Attribute\Autowire;


#[Route('/admin/matos/informatique', 'app_admin_matos_informatique_')]
final class MatosInformtatiqueController extends AbstractController
{
    #[Route(name: 'index', methods: ['GET'])]
    public function index(MatosInformatiqueRepository $matosInformatiqueRepository): Response
    {
        return $this->render('admin/matos_informatique/index.html.twig', [
            'matos_informatiques' => $matosInformatiqueRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'create', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, SpecificationsMatosInformatique $specificationsMatosInformatique): Response
    {
        $matosInformatique = new MatosInformatique();
        $form = $this->createForm(MatosInformatiqueType::class, $matosInformatique);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $specFile = $form->get('specification')->getData();
            if ($specFile) {
                $specPath = $specificationsMatosInformatique->uploadSpecMatosInfo($specFile);
                $matosInformatique->setSpecification($specPath);
            }
            $entityManager->persist($matosInformatique);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_matos_informatique_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/matos_informatique/new.html.twig', [
            'matos_informatique' => $matosInformatique,
            'form' => $form,
        ]);
    }

    #[Route('/upload', name: 'upload', methods: ['GET', 'POST'])]
    public function uploadList(Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UploadFileForm::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Handle the file upload and processing logic here
            // ...
            $file= $form->get('excel_file')->getData();
            $extension= $file->guessExtension();
            if($file && in_array($extension, ['xlsx','xls'])) {
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
                    $existingMatosInfo = $entityManager->getRepository(MatosInformatique::class)->findOneBy(['sn_matos' => $row[3]]);
                    if ($existingMatosInfo) {
                        $this->addFlash('error', 'L\'information personnelle avec le CIN ' . $row[3] . ' existe déjà.');
                        continue;
                    }
                    $dateString= $row[4];
                    if(!empty($dateString)){
                        $dateReception= \DateTime::createFromFormat('Y-m-d', $dateString);
                        if($dateReception != false){
                            $matosInfo = new MatosInformatique();
                            // Assuming the columns in the Excel file match the InfoPerso entity fields
                            $matosInfo->setTypeMatos($row[0]);
                            $matosInfo->setMarqueMatos($row[1]);
                            $matosInfo->setModeleMatos($row[2]);
                            $matosInfo->setSnMatos($row[3]);
                            $matosInfo->setDateReception($dateReception);
                            //$entityManager->flush();
                            // Add other fields as necessary
                            $entityManager->persist($matosInfo);
                        } else {
                            throw new \Exception("Format de date invalide : $dateString");
                        }
                    }
                }
                $entityManager->flush();
            }

            elseif($file && $extension === 'pdf'){
                $parser= new Parser();
                $pdf= $parser->parseFile($file->getPathname());
                $text= $pdf->getText();

                $lignes= explode("\n", $text);

                foreach($lignes as $ligne){
                    $cols=preg_split('/\s+/', trim($ligne));
                    if(count($cols)>=4){
                        $matosInfo= new MatosInformatique();
                        $matosInfo->setTypeMatos($cols[0]);
                        $matosInfo->setMarqueMatos($cols[1]);
                        $matosInfo->setModeleMatos($cols[2]);
                        $matosInfo->setSnMatos($cols[3]);

                        $existMatosInfo= $entityManager->getRepository(MatosInformatique::class)->findOneBy(['sn_matos'=>$matosInfo->getSnMatos()]);
                        if(!$existMatosInfo){
                            $entityManager->persist($matosInfo);
                        }
                    }
                }
                $entityManager->flush();
            }

            else{
                $this->addFlash('error', 'Format excel ou pdf sont permis');
            }

            $this->addFlash('success', 'Fichier importé avec succès.');
            return $this->redirectToRoute('app_admin_matos_informatique_index');
        }

        return $this->render('admin/uploadfiles/upload.html.twig', [
            'form' => $form->createView(),
            'nom_fichier' => 'InfoPerso', // This can be dynamic based on the file type
            'redirectCancelRoute' => 'app_admin_matos_informatique_index', // Redirect route after cancellation
        ]);
    }


    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(MatosInformatique $matosInformatique): Response
    {
        return $this->render('admin/matos_informatique/show.html.twig', [
            'matos_informatique' => $matosInformatique,
        ]);
    }

    #[Route('/{id}/show_specs', name: 'show_specs', methods: ['GET'])]
    public function showSpecs(MatosInformatique $matosInformatique, SpecificationsMatosInformatique $specificationsMatosInformatique): Response
    {

        if (!$matosInformatique) {
            throw $this->createNotFoundException("Matériel non trouvé.");
        }

        $stream = $specificationsMatosInformatique->getSpecMatosInfoStream($matosInformatique->getSpecification());

        if (!$stream) {
            throw $this->createNotFoundException("Spécifications non trouvées.");
        }

        return new Response(stream_get_contents($stream), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="specifications_' . $matosInformatique->getId() . '.pdf"',
        ]);
    }

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, MatosInformatique $matosInformatique, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(MatosInformatiqueType::class, $matosInformatique);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_matos_informatique_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/matos_informatique/edit.html.twig', [
            'matos_informatique' => $matosInformatique,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, MatosInformatique $matosInformatique, EntityManagerInterface $entityManager, #[Autowire(service: 'sftp.storage')]  FilesystemOperator $sftpStorage): Response
    {
        if(!$matosInformatique){
            $this->addFlash('Erreur', 'Matériel introuvable :(');
            return $this->redirectToRoute('app_admin_matos_informatique_index');
        }
        if ($this->isCsrfTokenValid('delete'.$matosInformatique->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($matosInformatique);
            $entityManager->flush();
        }

        $specPath= $matosInformatique->getSpecification();
        if($specPath && $sftpStorage->fileExists($specPath)){
            try{
                $sftpStorage->delete($specPath);
            } catch(\Throwable $e){
                $this->addFlash('Alerte', 'Document non supprimé sur le serveur distant: '.$e->getMessage());
            }
        }

        return $this->redirectToRoute('app_admin_matos_informatique_index', [], Response::HTTP_SEE_OTHER);
    }
}
