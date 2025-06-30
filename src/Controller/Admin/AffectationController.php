<?php

namespace App\Controller\Admin;

use App\Entity\Affectation;
use App\Form\AffectationForm;
use App\Repository\AffectationRepository;
use Doctrine\ORM\EntityManagerInterface;
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
