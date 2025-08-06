<?php

namespace App\Controller\Admin;

use App\Entity\CarteProfessionnelle;
use App\Form\CarteProfessionnelleType;
use App\Repository\CarteProfessionnelleRepository;
use App\Service\PhotoService;
use Doctrine\ORM\EntityManagerInterface;
use League\Flysystem\FilesystemOperator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

#[Route('/admin/carte/professionnelle', 'app_admin_carte_professionnelle_')]
final class CarteProfessionnelleController extends AbstractController
{
    #[Route(name: 'index', methods: ['GET'])]
    public function index(CarteProfessionnelleRepository $carteProfessionnelleRepository): Response
    {
        return $this->render('admin/carte_professionnelle/index.html.twig', [
            'carte_professionnelles' => $carteProfessionnelleRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'create', methods: ['GET', 'POST'])]
    public function new(Request $request, PhotoService $photoService, EntityManagerInterface $entityManager): Response
    {
        $carteProfessionnelle = new CarteProfessionnelle();
        $form = $this->createForm(CarteProfessionnelleType::class, $carteProfessionnelle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $carteProfessionnelle->setStatusImpression("En Attente");

            $imageFile = $form->get('photo_agent')->getData();
            if ($imageFile) {
                $imagePath=$photoService->uploadPhoto($imageFile);
                $carteProfessionnelle->setPhotoAgent($imagePath);
            }

            $entityManager->persist($carteProfessionnelle);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_carte_professionnelle_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/carte_professionnelle/new.html.twig', [
            'carte_professionnelle' => $carteProfessionnelle,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(CarteProfessionnelle $carteProfessionnelle, FilesystemOperator $sftpStorage): Response
    {

        if(!$carteProfessionnelle){
            throw $this->createNotFoundException('Personne non identifiée :(');
        }
        $photoFileName= $carteProfessionnelle->getPhotoAgent();
        $qrCodeFileName= $carteProfessionnelle->getIdentite()->getAgent()->getIdentification()->getQrCode();

        if($sftpStorage->fileExists($photoFileName)){
            $photoImageData= $sftpStorage->read($photoFileName);
            $qrCodeImageData=$sftpStorage->read($qrCodeFileName);
            $photoBase64=base64_encode($photoImageData);
            $qrCodeBase64=base64_encode($qrCodeImageData);
        }
        else{
            $photoBase64= null;
            $qrCodeBase64=null;
        }

        return $this->render('admin/carte_professionnelle/show.html.twig', [
             'carte_professionnelle' => $carteProfessionnelle,
            'photoBase64'=>$photoBase64,
            'qrCodeBase64'=>$qrCodeBase64,
        ]);
    }

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, CarteProfessionnelle $carteProfessionnelle, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CarteProfessionnelleType::class, $carteProfessionnelle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_carte_professionnelle_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/carte_professionnelle/edit.html.twig', [
            'carte_professionnelle' => $carteProfessionnelle,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, CarteProfessionnelle $carteProfessionnelle, #[Autowire(service: 'sftp.storage')] FilesystemOperator $sftpStorage, EntityManagerInterface $entityManager): Response
    {
        if(!$carteProfessionnelle){
            $this->addFlash('Erreur', 'Carte professionnelle introuvable :(');
            return $this->redirectToRoute('app_admin_carte_professionnelle_index');
        }
        if ($this->isCsrfTokenValid('delete'.$carteProfessionnelle->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($carteProfessionnelle);
            $entityManager->flush();
        }

        $photoPath= $carteProfessionnelle->getPhotoAgent();
        if($photoPath && $sftpStorage->fileExists($photoPath)){
            try{
                $sftpStorage->delete($photoPath);
            } catch(\Throwable $e){
                $this->addFlash('Alerte', 'QR Code non supprimé sur le serveur distant: '.$e->getMessage());
            }
        }

        return $this->redirectToRoute('app_admin_carte_professionnelle_index', [], Response::HTTP_SEE_OTHER);
    }
}
