<?php

namespace App\Controller\Admin;

use App\Entity\Agent;
use App\Entity\User;
use App\Form\UploadFileForm;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/user', 'app_admin_user_')]
#[IsGranted("ROLE_SUPERADMIN")]
final class UserController extends AbstractController
{
    #[Route(name: 'index', methods: ['GET'])]
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('admin/user/index.html.twig', [
            'users' => $userRepository->findAllUserAgents(),
        ]);
    }

    #[Route('/new', name: 'create', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $userPasswordHasherInterface): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $userPasswordHasherInterface->hashPassword(
                    $user,
                    "passer123"
                )
            );
            $user->setIsActive(false);
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/user/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }
    #[Route('/upload', name: 'upload', methods: ['GET', 'POST'])]
    public function upload(Request $request, UserPasswordHasherInterface $userPasswordHasherInterface, EntityManagerInterface $entityManager): Response
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
                    $agent=$entityManager->getRepository(Agent::class)->findOneBy(['matricule' => $row[3]]);
                    if (!$agent) {
                        $this->addFlash('error', 'Cet agent de matricule ' . $row[3] . ' n\'existe pas.');
                        continue;
                    }

                    // Vérification des doublons
                    $existingUser = $entityManager->getRepository(User::class)->findOneBy(['email' => $row[2]]);
                    if ($existingUser) {
                        $this->addFlash('error', 'Cet utilisateur ' . $row[1] . ' existe déjà.');
                        continue;
                    }
                    $user = new User();
                    $user->setAgent($agent);
                    $user->setEmail( $row[2]); // Assuming the first column is the name
                    $user->setPseudo($row[1]); // Assuming the first column is the name
                    $user->setPassword(
                        $userPasswordHasherInterface->hashPassword(
                            $user,
                            "passer123"
                        )
                    );
                    $user->setRoles(["ROLE_USER"]);// Assuming the second column is the description
                    // Add other fields as necessary
                    $entityManager->persist($user);
                }
                $entityManager->flush();
            }

            $this->addFlash('success', 'Fichier importé avec succès.');
            return $this->redirectToRoute('app_admin_user_index');
        }

        return $this->render('admin/uploadfiles/upload.html.twig', [
            'form' => $form->createView(),
            'nom_fichier' => 'Utilisateur', // This can be dynamic based on the file type
            'redirectCancelRoute' => 'app_admin_user_index', // Redirect route after cancellation
        ]);
    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->render('admin/user/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_admin_user_index', [], Response::HTTP_SEE_OTHER);
    }
}
