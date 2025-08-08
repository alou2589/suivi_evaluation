<?php

namespace App\Controller\Admin;

use App\Entity\Attribution;
use App\Form\AttributionType;
use App\Repository\AttributionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/attribution','app_admin_attribution_')]
final class AttributionController extends AbstractController
{
    #[Route(name: 'index', methods: ['GET'])]
    public function index(AttributionRepository $attributionRepository): Response
    {
        return $this->render('admin/attribution/index.html.twig', [
            'attributions' => $attributionRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'create', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $attribution = new Attribution();
        $form = $this->createForm(AttributionType::class, $attribution);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($attribution);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_attribution_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/attribution/new.html.twig', [
            'attribution' => $attribution,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(Attribution $attribution): Response
    {
        return $this->render('admin/attribution/show.html.twig', [
            'attribution' => $attribution,
        ]);
    }

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Attribution $attribution, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AttributionType::class, $attribution);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_attribution_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/attribution/edit.html.twig', [
            'attribution' => $attribution,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, Attribution $attribution, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$attribution->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($attribution);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_admin_attribution_index', [], Response::HTTP_SEE_OTHER);
    }
}
