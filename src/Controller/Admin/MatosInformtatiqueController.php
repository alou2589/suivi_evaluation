<?php

namespace App\Controller\Admin;

use App\Entity\MatosInformatique;
use App\Form\MatosInformatiqueType;
use App\Repository\MatosInformatiqueRepository;
use App\Service\SpecificationsMatosInformatique;
use Doctrine\ORM\EntityManagerInterface;
use League\Flysystem\FilesystemOperator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\DependencyInjection\Attribute\Autowire;


#[Route('/admin/matos/informtatique', 'app_admin_matos_informtatique_')]
final class MatosInformtatiqueController extends AbstractController
{
    #[Route(name: 'index', methods: ['GET'])]
    public function index(MatosInformatiqueRepository $matosInformatiqueRepository): Response
    {
        return $this->render('admin/matos_informtatique/index.html.twig', [
            'matos_informatiques' => $matosInformatiqueRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'create', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, SpecificationsMatosInformatique $specificationsMatosInformatique): Response
    {
        $matosInformatique = new MatosInformatique();
        $form = $this->createForm(MatosInformatiqueType::class, $matosInformatique);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $specFile = $form->get('specification')->getData();
            if ($specFile) {
                $specPath = $specificationsMatosInformatique->uploadSpecMatosInfo($specFile);
                $matosInformatique->setSpecification($specPath);
            }
            $entityManager->persist($matosInformatique);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_matos_informtatique_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/matos_informtatique/new.html.twig', [
            'matos_informatique' => $matosInformatique,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(MatosInformatique $matosInformatique): Response
    {
        return $this->render('admin/matos_informtatique/show.html.twig', [
            'matos_informatique' => $matosInformatique,
        ]);
    }

    #[Route('/{id}/show_specs', name: 'show_specs', methods: ['GET'])]
    public function showSpecs(MatosInformatique $matosInformatique, SpecificationsMatosInformatique $specificationsMatosInformatique): Response
    {

        if (!$matosInformatique) {
            throw $this->createNotFoundException("Matériel non trouvé.");
        }

        $stream = $specificationsMatosInformatique->getSpecMatosInfoStream($matosInformatique->getSpecification());

        if (!$stream) {
            throw $this->createNotFoundException("Spécifications non trouvées.");
        }

        return new Response(stream_get_contents($stream), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="specifications_' . $matosInformatique->getId() . '.pdf"',
        ]);
    }

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, MatosInformatique $matosInformatique, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(MatosInformatiqueType::class, $matosInformatique);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_matos_informtatique_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/matos_informtatique/edit.html.twig', [
            'matos_informatique' => $matosInformatique,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, MatosInformatique $matosInformatique, EntityManagerInterface $entityManager, #[Autowire(service: 'sftp.storage')]  FilesystemOperator $sftpStorage): Response
    {
        if(!$matosInformatique){
            $this->addFlash('Erreur', 'Matériel introuvable :(');
            return $this->redirectToRoute('app_admin_matos_informtatique_index');
        }
        if ($this->isCsrfTokenValid('delete'.$matosInformatique->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($matosInformatique);
            $entityManager->flush();
        }

        $specPath= $matosInformatique->getSpecification();
        if($specPath && $sftpStorage->fileExists($specPath)){
            try{
                $sftpStorage->delete($specPath);
            } catch(\Throwable $e){
                $this->addFlash('Alerte', 'Document non supprimé sur le serveur distant: '.$e->getMessage());
            }
        }

        return $this->redirectToRoute('app_admin_doc_admin_index', [], Response::HTTP_SEE_OTHER);
    }
}
