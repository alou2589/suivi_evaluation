<?php

namespace App\Controller\Admin;

use App\Entity\MarqueMatos;
use App\Form\MarqueMatosType;
use App\Repository\MarqueMatosRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/marque/matos')]
final class MarqueMatosController extends AbstractController
{
    #[Route(name: 'app_admin_marque_matos_index', methods: ['GET'])]
    public function index(MarqueMatosRepository $marqueMatosRepository): Response
    {
        return $this->render('admin/marque_matos/index.html.twig', [
            'marque_matos' => $marqueMatosRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_admin_marque_matos_new', methods: ['GET', 'POST'])]
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

    #[Route('/{id}', name: 'app_admin_marque_matos_show', methods: ['GET'])]
    public function show(MarqueMatos $marqueMato): Response
    {
        return $this->render('admin/marque_matos/show.html.twig', [
            'marque_mato' => $marqueMato,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_marque_matos_edit', methods: ['GET', 'POST'])]
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

    #[Route('/{id}', name: 'app_admin_marque_matos_delete', methods: ['POST'])]
    public function delete(Request $request, MarqueMatos $marqueMato, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$marqueMato->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($marqueMato);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_admin_marque_matos_index', [], Response::HTTP_SEE_OTHER);
    }
}
