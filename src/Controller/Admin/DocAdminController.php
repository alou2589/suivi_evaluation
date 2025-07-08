<?php

namespace App\Controller\Admin;

use App\Entity\DocumentAdministratif;
use App\Form\DocumentAdministratifForm;
use App\Repository\DocumentAdministratifRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/doc/admin')]
final class DocAdminController extends AbstractController
{
    #[Route(name: 'app_admin_doc_admin_index', methods: ['GET'])]
    public function index(DocumentAdministratifRepository $documentAdministratifRepository): Response
    {
        return $this->render('admin/doc_admin/index.html.twig', [
            'document_administratifs' => $documentAdministratifRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_admin_doc_admin_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $documentAdministratif = new DocumentAdministratif();
        $form = $this->createForm(DocumentAdministratifForm::class, $documentAdministratif);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($documentAdministratif);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_doc_admin_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/doc_admin/new.html.twig', [
            'document_administratif' => $documentAdministratif,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_doc_admin_show', methods: ['GET'])]
    public function show(DocumentAdministratif $documentAdministratif): Response
    {
        return $this->render('admin/doc_admin/show.html.twig', [
            'document_administratif' => $documentAdministratif,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_doc_admin_edit', methods: ['GET', 'POST'])]
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

    #[Route('/{id}', name: 'app_admin_doc_admin_delete', methods: ['POST'])]
    public function delete(Request $request, DocumentAdministratif $documentAdministratif, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$documentAdministratif->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($documentAdministratif);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_admin_doc_admin_index', [], Response::HTTP_SEE_OTHER);
    }
}
