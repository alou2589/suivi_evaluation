<?php

namespace App\Controller\Admin;

use App\Entity\Visite;
use App\Form\VisiteType;
use App\Repository\VisiteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/visite')]
final class VisiteController extends AbstractController
{
    #[Route(name: 'app_admin_visite_index', methods: ['GET'])]
    public function index(VisiteRepository $visiteRepository): Response
    {
        return $this->render('admin/visite/index.html.twig', [
            'visites' => $visiteRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_admin_visite_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $visite = new Visite();
        $form = $this->createForm(VisiteType::class, $visite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($visite);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_visite_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/visite/new.html.twig', [
            'visite' => $visite,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_visite_show', methods: ['GET'])]
    public function show(Visite $visite): Response
    {
        return $this->render('admin/visite/show.html.twig', [
            'visite' => $visite,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_visite_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Visite $visite, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(VisiteType::class, $visite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_visite_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/visite/edit.html.twig', [
            'visite' => $visite,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_visite_delete', methods: ['POST'])]
    public function delete(Request $request, Visite $visite, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$visite->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($visite);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_admin_visite_index', [], Response::HTTP_SEE_OTHER);
    }
}
