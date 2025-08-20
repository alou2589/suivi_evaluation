<?php

namespace App\Controller\Admin;

use App\Entity\Agent;
use App\Form\AgentForm;
use App\Repository\AgentRepository;
use App\Repository\AffectationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\User;

#[Route('/admin/agent', name:'app_admin_agent_')]
final class AgentController extends AbstractController
{
    #[Route(name: 'index', methods: ['GET'])]
    public function index(AgentRepository $agentRepository): Response
    {
        return $this->render('admin/agent/index.html.twig', [
            'agents' => $agentRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'create', methods: ['GET', 'POST'])]
    public function new(Request $request, UserPasswordHasherInterface $hashPassword, EntityManagerInterface $entityManager, AgentRepository $agentRepository): Response
    {
        $agent = new Agent();
        $form = $this->createForm(AgentForm::class, $agent);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user=new User();
            $prenom= strtolower($this->sanitize($agent->getIdentification()->getPrenom()));
            $nom= strtolower($this->sanitize($agent->getIdentification()->getNom()));
            $domain= 'industriecommerce.gouv.sn';
            $nbagents= $agentRepository->searchdoublonsAgentPrenomNomCount($prenom, $nom);
            if($nbagents == 0){
                $email= "{$prenom}.{$nom}@{$domain}";
            } else {
                $email= "{$prenom}." . ($nbagents + 1) . ".{$nom}@{$domain}";
            }
            $password= 'passer123';
            $user->setEmail($email);
            $user->setAgent($agent);
            $user->setRoles(['ROLE_USER']);
            $user->setPassword(
                $hashPassword->hashPassword(
                    $user,
                    $password
                )
            );
            $user->setCreatedAt(new \DateTimeImmutable());
            $entityManager->persist($agent);
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_agent_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/agent/new.html.twig', [
            'agent' => $agent,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(Agent $agent, AffectationRepository $affectationRepository): Response
    {
        // Fetching the last affectation of the agent
        $lastAffectation = $affectationRepository->findLastAffectationByAgent($agent);
        return $this->render('admin/agent/show.html.twig', [
            'agent' => $agent,
            'lastAffectation' => $lastAffectation,
        ]);
    }

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Agent $agent, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AgentForm::class, $agent);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_agent_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/agent/edit.html.twig', [
            'agent' => $agent,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, Agent $agent, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$agent->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($agent);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_admin_agent_index', [], Response::HTTP_SEE_OTHER);
    }

    public function sanitize(string $text): string
    {
        $text= iconv('UTF-8', 'UTF-8//TRANSLIT', $text);
        $text= preg_replace('/[^a-zA-Z0-9]/', '', $text);
        return $text;
    }
}
