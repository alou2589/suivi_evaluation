<?php

namespace App\Controller\Admin;

use App\Entity\Agent;
use App\Entity\InfoPerso;
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
use App\Form\UploadFileForm;
use PhpOffice\PhpSpreadsheet\IOFactory;

#[Route('/admin/agent', name:'app_admin_agent_')]
final class AgentController extends AbstractController
{
    #[Route(name: 'index', methods: ['GET'])]
    public function index(AgentRepository $agentRepository): Response
    {
        return $this->render('admin/agent/index.html.twig', [
            'agents' => $agentRepository->findAllAgents(),
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
            $nom= strtolower(string: $this->sanitize($agent->getIdentification()->getNom()));
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

       #[Route('/upload', name: 'upload', methods: ['GET', 'POST'])]
    public function upload(Request $request,UserPasswordHasherInterface $userPasswordHasherInterface, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UploadFileForm::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Handle the file upload and processing logic here
            // ...
            $file= $form->get('excel_file')->getData();
            if ($file) {
                // Process the uploaded file (e.g., read data, save to database)
                $spreadsheet= IOFactory::load($file->getPathname());
                $sheet=$spreadsheet->getActiveSheet();
                $rows = $sheet->toArray();
                foreach ($rows as $index=> $row) {
                    if ($index === 0) {
                        // Skip header row
                        continue;
                    }
                    $identification=$entityManager->getRepository(InfoPerso::class)->findOneBy(['cin'=>$row[1]]);
                    if (!$identification) {
                        $this->addFlash('error', 'La personne ' . $row[0].' '.$row[1] . ' n\'existe pas.');
                        continue;
                    }

                    // Vérification des doublons
                    $existingAgent = $entityManager->getRepository(Agent::class)->findOneBy(['matricule' => $row[2]]);
                    if ($existingAgent) {
                        $this->addFlash('error', ' Cet agent de matricule ' . $row[2] . ' existe déjà.');
                        continue;
                    }
                    $dateString= $row[10];
                    if(!empty($dateString)){
                        $dateRecrutement= \DateTime::createFromFormat('Y-m-d', $dateString);
                        if($dateRecrutement != false){
                            $agent = new Agent();
                            // Assuming the columns in the Excel file match the InfoPerso entity fields
                            $agent->setIdentification($identification);
                            $agent->setMatricule($row[2]);
                            $agent->setFonction($row[3]);
                            $agent->setCadreStatuaire($row[4]);
                            $agent->setHierarchie($row[5]);
                            $agent->setGrade($row[6]);
                            $agent->setEchelon($row[7]);
                            $agent->setDecisionContrat($row[8]);
                            $agent->setNumeroDecisionContrat($row[9]);
                            $agent->setDateRecrutement($dateRecrutement);
                            $agent->setBanque($row[11]);
                            $agent->setNumeroCompte($row[12]);
                            $agent->setStatus($row[13]);
                            //$entityManager->flush();
                            // Add other fields as necessary
                            $entityManager->persist($agent);
                            //$user=new User();
                            //$prenom= strtolower($this->sanitize($identification->getPrenom()));
                            //$nom= strtolower($this->sanitize($identification->getNom()));
                            //$nbagents= $entityManager->getRepository(Agent::class)->searchdoublonsAgentPrenomNomCount($prenom, $nom);
                            //if($nbagents==0){
                            //    $email="{$prenom}.{$nom}@industriecommerce.gouv.sn";
                            //} else {
                            //    $email= "{$prenom}." . ($nbagents + 1) . ".{$nom}@industriecommerce.gouv.sn";
                            //}
                            //$user->setAgent($agent);
                            //$user->setEmail($email);
                            //$user->setPassword(
                            //    $userPasswordHasherInterface->hashPassword(
                            //        $user,
                            //        "passer123"
                            //    )
                            //);
                            //$entityManager->persist($user);
                        } else {
                            throw new \Exception("Format de date invalide : $dateString");
                        }
                    }
                }
                $entityManager->flush();


            }

            $this->addFlash('success', 'Fichier importé avec succès.');
            return $this->redirectToRoute('app_admin_agent_index');
        }

        return $this->render('admin/uploadfiles/upload.html.twig', [
            'form' => $form->createView(),
            'nom_fichier' => 'Agent', // This can be dynamic based on the file type
            'redirectCancelRoute' => 'app_admin_agent_index', // Redirect route after cancellation
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
