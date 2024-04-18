<?php

// src/Controller/TutoratController/MentorController.php
namespace App\Controller\TutoratController;

use App\Entity\Mentor;
use App\Form\MentorType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\UserService;
use App\Service\FileUploadService;

class MentorController extends AbstractController
{
    private UserService $userService;
    private FileUploadService $fileUploadService;
    private EntityManagerInterface $entityManager;

    public function __construct(UserService $userService, FileUploadService $fileUploadService, EntityManagerInterface $entityManager)
    {
        $this->userService = $userService;
        $this->fileUploadService = $fileUploadService;
        $this->entityManager = $entityManager;
    }

    #[Route('/tutorat/mentor', name: 'registerMentor')]
    public function create(Request $request): Response
    {
        if (!$this->userService->isLogin()) {
            $this->addFlash('danger', 'Vous devez être connecté pour accéder à cette page.');
            return $this->redirectToRoute('login');
        }

        $mentor = new Mentor();
        $form = $this->createForm(MentorType::class, $mentor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('doc')->getData();
            if ($file) {
                try {
                    $newFilename = $this->fileUploadService->uploadFile($file);
                    $mentor->setDocPath($newFilename);
                } catch (FileException $e) {
                    $this->addFlash('danger', 'Une erreur est survenue lors de l\'envoi de votre fichier.');
                    return $this->redirectToRoute('tutorat');
                }
            }

            $mentor->setUser($this->userService->getUser());
            $this->entityManager->persist($mentor);
            $this->entityManager->flush();

            $this->addFlash('success', 'Votre inscription a bien été enregistrée. Vous recevrez une réponse dans les 24 heures.');
            return $this->redirectToRoute('tutorat');
        }

        return $this->render('tutorat/mentorForm.html.twig', [
            'mentorForm' => $form->createView(),
        ]);
    }
}
