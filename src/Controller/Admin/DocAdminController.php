<?php

namespace App\Controller\Admin;

use App\Entity\DocumentAdministratif;
use App\Form\DocumentAdministratifForm;
use App\Repository\DocumentAdministratifRepository;
use App\Service\DocAdminService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use League\Flysystem\FilesystemOperator;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

#[Route('/admin/doc/admin',  'app_admin_doc_admin_')]
final class DocAdminController extends AbstractController
{
    #[Route(name: 'index', methods: ['GET'])]
    public function index(DocumentAdministratifRepository $documentAdministratifRepository): Response
    {
        return $this->render('admin/doc_admin/index.html.twig', [
            'document_administratifs' => $documentAdministratifRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'create', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, DocAdminService $docAdminService): Response
    {
        $documentAdministratif = new DocumentAdministratif();
        $form = $this->createForm(DocumentAdministratifForm::class, $documentAdministratif);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $docFile = $form->get('document')->getData();
            if ($docFile) {
                $docPath = $docAdminService->uploadDocument($docFile);
                $documentAdministratif->setDocument($docPath);
            }
            $entityManager->persist($documentAdministratif);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_doc_admin_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/doc_admin/new.html.twig', [
            'document_administratif' => $documentAdministratif,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(DocumentAdministratif $documentAdministratif): Response
    {
        return $this->render('admin/doc_admin/show.html.twig', [
            'document_administratif' => $documentAdministratif,
        ]);
    }

    #[Route('/{id}/show_document', name: 'show_document', methods: ['GET'])]
    public function showDocument(DocumentAdministratif $documentAdministratif, DocAdminService $docAdminService): Response
    {

        if (!$documentAdministratif) {
            throw $this->createNotFoundException("Document non trouvé.");
        }

        $stream = $docAdminService->getDocumentStream($documentAdministratif->getDocument());

        if (!$stream) {
            throw $this->createNotFoundException("Document non trouvé.");
        }

        return new Response(stream_get_contents($stream), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="document_' . $documentAdministratif->getAgent()->getMatricule() . '.pdf"',
        ]);
    }

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, DocumentAdministratif $documentAdministratif, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(DocumentAdministratifForm::class, $documentAdministratif);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_doc_admin_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/doc_admin/edit.html.twig', [
            'document_administratif' => $documentAdministratif,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, DocumentAdministratif $documentAdministratif, EntityManagerInterface $entityManager, #[Autowire(service: 'sftp.storage')] FilesystemOperator $sftpStorage): Response
    {
        if(!$documentAdministratif){
            $this->addFlash('Erreur', 'Document administratif introuvable :(');
            return $this->redirectToRoute('app_admin_doc_admin_index');
        }
        if ($this->isCsrfTokenValid('delete'.$documentAdministratif->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($documentAdministratif);
            $entityManager->flush();
        }

        $docPath= $documentAdministratif->getDocument();
        if($docPath && $sftpStorage->fileExists($docPath)){
            try{
                $sftpStorage->delete($docPath);
            } catch(\Throwable $e){
                $this->addFlash('Alerte', 'Document non supprimé sur le serveur distant: '.$e->getMessage());
            }
        }

        return $this->redirectToRoute('app_admin_doc_admin_index', [], Response::HTTP_SEE_OTHER);
    }
}
