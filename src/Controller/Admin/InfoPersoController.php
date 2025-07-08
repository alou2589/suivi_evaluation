<?php

namespace App\Controller\Admin;

use App\Entity\InfoPerso;
use App\Form\InfoPersoForm;
use App\Repository\InfoPersoRepository;
use App\Service\AesEncryptDecrypt;
use App\Service\QrCodeGenerator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/admin/info/perso', name: 'app_admin_info_perso_')]
final class InfoPersoController extends AbstractController
{
    #[Route(name: 'index', methods: ['GET'])]
    public function index(InfoPersoRepository $infoPersoRepository): Response
    {
        return $this->render('admin/info_perso/index.html.twig', [
            'info_persos' => $infoPersoRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'create', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, QrCodeGenerator $qrCodeGenerator, AesEncryptDecrypt $aesEncryptDecrypt, InfoPersoRepository $infoPersoRepository, SluggerInterface $slugger): Response
    {
        $infoPerso = new InfoPerso();
        $qr_code=null;
        $form = $this->createForm(InfoPersoForm::class, $infoPerso);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $infoPerso->setQrCode($qr_code);
            $entityManager->persist($infoPerso);
            $entityManager->flush();

            $qr_code = $qrCodeGenerator->generateQrCode($infoPerso->getTelephone(), $infoPerso->getId());
            $infoPerso->setQrCode($aesEncryptDecrypt->encrypt((string)$qr_code));
            $entityManager->persist($infoPerso);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_info_perso_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('admin/info_perso/new.html.twig', [
            'info_perso' => $infoPerso,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(InfoPerso $infoPerso, AesEncryptDecrypt $aesEncryptDecrypt): Response
    {
        $qrCode = $aesEncryptDecrypt->decrypt($infoPerso->getQrCode());
        return $this->render('admin/info_perso/show.html.twig', [
            'info_perso' => $infoPerso,
            'qr_code' => $qrCode,
        ]);
    }

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, InfoPerso $infoPerso, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(InfoPersoForm::class, $infoPerso);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_info_perso_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/info_perso/edit.html.twig', [
            'info_perso' => $infoPerso,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, InfoPerso $infoPerso, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$infoPerso->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($infoPerso);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_admin_info_perso_index', [], Response::HTTP_SEE_OTHER);
    }
}
