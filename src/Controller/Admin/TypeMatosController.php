<?php

namespace App\Controller\Admin;

use App\Entity\TypeMatos;
use App\Form\TypeMatosType;
use App\Repository\TypeMatosRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/type/matos', 'app_admin_type_matos_')]
final class TypeMatosController extends AbstractController
{
    #[Route(name: 'index', methods: ['GET'])]
    public function index(TypeMatosRepository $typeMatosRepository): Response
    {
        return $this->render('admin/type_matos/index.html.twig', [
            'type_matos' => $typeMatosRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'create', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $typeMato = new TypeMatos();
        $form = $this->createForm(TypeMatosType::class, $typeMato);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($typeMato);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_type_matos_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/type_matos/new.html.twig', [
            'types' => $typeMato,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(TypeMatos $typeMato): Response
    {
        return $this->render('admin/type_matos/show.html.twig', [
            'type_mato' => $typeMato,
        ]);
    }

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, TypeMatos $typeMato, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TypeMatosType::class, $typeMato);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_type_matos_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/type_matos/edit.html.twig', [
            'type_mato' => $typeMato,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, TypeMatos $typeMato, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$typeMato->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($typeMato);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_admin_type_matos_index', [], Response::HTTP_SEE_OTHER);
    }
}
