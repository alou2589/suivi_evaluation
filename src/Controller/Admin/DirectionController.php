<?php

namespace App\Controller\Admin;

use App\Entity\Direction;
use App\Form\DirectionForm;
use App\Repository\AffectationRepository;
use App\Repository\DirectionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;

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
