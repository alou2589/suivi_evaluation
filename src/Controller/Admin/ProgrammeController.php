<?php

namespace App\Controller\Admin;

use App\Entity\Programme;
use App\Form\ProgrammeForm;
use App\Repository\ProgrammeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/programme', name: 'app_admin_programme_')]
final class ProgrammeController extends AbstractController
{
    #[Route(name: 'index', methods: ['GET'])]
    public function index(ProgrammeRepository $programmeRepository): Response
    {
        return $this->render('admin/programme/index.html.twig', [
            'programmes' => $programmeRepository->findWithAgent(),
        ]);
    }

    #[Route('/new', name: 'create', methods: ['GET', 'POST'])]
    public function new(Request $request,ProgrammeRepository $programmeRepository, EntityManagerInterface $entityManager): Response
    {
        $programme = new Programme();
        $form = $this->createForm(ProgrammeForm::class, $programme);
        $programmes = $programmeRepository->findBy([], ['id' => 'DESC'], 1, 0);
        $numero = end($programmes);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $programmes == null ? $programme->setCodeProgramme("Programme 1") : $programme->setCodeProgramme("Programme " . (int)array_key_last(explode(" ", $numero->getCodeProgramme())) + 1);
            $entityManager->persist($programme);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_programme_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/programme/new.html.twig', [
            'programme' => $programme,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(Programme $programme): Response
    {
        return $this->render('admin/programme/show.html.twig', [
            'programme' => $programme,
        ]);
    }

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Programme $programme, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ProgrammeForm::class, $programme);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_programme_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/programme/edit.html.twig', [
            'programme' => $programme,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, Programme $programme, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$programme->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($programme);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_admin_programme_index', [], Response::HTTP_SEE_OTHER);
    }
}
