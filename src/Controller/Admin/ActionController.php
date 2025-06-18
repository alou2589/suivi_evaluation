<?php

namespace App\Controller\Admin;

use App\Entity\Action;
use App\Form\ActionForm;
use App\Repository\ActionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/action', name: 'app_admin_action_')]
final class ActionController extends AbstractController
{
    #[Route(name: 'index', methods: ['GET'])]
    public function index(ActionRepository $actionRepository): Response
    {
        return $this->render('admin/action/index.html.twig', [
            'actions' => $actionRepository->findWithAgentAndProgramme(),
        ]);
    }

    #[Route('/new', name: 'create', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $action = new Action();
        $form = $this->createForm(ActionForm::class, $action);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($action);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_action_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/action/new.html.twig', [
            'action' => $action,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(Action $action): Response
    {
        return $this->render('admin/action/show.html.twig', [
            'action' => $action,
        ]);
    }

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Action $action, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ActionForm::class, $action);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_action_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/action/edit.html.twig', [
            'action' => $action,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, Action $action, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$action->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($action);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_admin_action_index', [], Response::HTTP_SEE_OTHER);
    }
}
