<?php

// src/Controller/TutoratController/MentorController.php
namespace App\Controller\TutoratController;

use App\Entity\Etudiant;
use App\Entity\Mentor;
use App\Entity\Program;
use App\Form\MentorType;
use App\Form\ProgramType;
use App\security\Role;
use App\Service\FileUploadService\FileUploadServiceImpl;
use App\Service\UserService\UserServiceImpl;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MentorController extends AbstractController
{
    private UserServiceImpl $userService;
    private FileUploadServiceImpl $fileUploadService;
    private EntityManagerInterface $entityManager;

    public function __construct(UserServiceImpl $userService, FileUploadServiceImpl $fileUploadService, EntityManagerInterface $entityManager)
    {
        $this->userService = $userService;
        $this->fileUploadService = $fileUploadService;
        $this->entityManager = $entityManager;
    }

    #[Route('/tutorat/register_mentor', name: 'registerMentor')]
    public function create(Request $request): Response
    {
        if (!$this->userService->isLogin()) {
            $this->addFlash('danger', 'Vous devez être connecté pour accéder à cette page.');
            return $this->redirectToRoute('login');
        }

        // Récupérer l'utilisateur connecté
        $currentUser = $this->userService->getUser();

        // Vérifier si l'utilisateur est déjà inscrit comme mentor
        $existingMentor = $this->entityManager->getRepository(Mentor::class)->findOneBy(['user' => $currentUser]);

        // Si l'utilisateur est déjà inscrit, afficher un message flash d'erreur
        if ($existingMentor) {
            $this->addFlash('error', 'Vous êtes déjà inscrit comme mentor.');
            return $this->redirectToRoute('tutorat');
        }

        $mentor = new Mentor();
        $form = $this->createForm(MentorType::class, $mentor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('Doc')->getData();
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

            // Ajouter le rôle "ROLE_MENTOR" à l'utilisateur connecté
            $user = $this->getUser();
            $user->addRole(Role::ROLE_MENTOR);
            $this->entityManager->persist($user);
            $this->entityManager->flush();

            $this->entityManager->persist($mentor);
            $this->entityManager->flush();

            $this->addFlash('success', 'Vous venez d\'être admis en tant que mentor. Veuillez vous reconnecter.');
            return $this->redirectToRoute('login');
        }

        return $this->render('tutorat/mentor/mentor_form.html.twig', [
            'mentorForm' => $form->createView(),
        ]);
    }
    #[Route('/programs/{program_id}/remove-student/{student_id}', name: 'remove_student_from_program', methods: ['DELETE', 'POST'])]
    public function removeStudentFromProgram(EntityManagerInterface $entityManager, int $program_id, int $student_id): Response
    {
        // Récupérer le programme et l'étudiant
        $program = $entityManager->getRepository(Program::class)->find($program_id);
        $student = $entityManager->getRepository(Etudiant::class)->find($student_id);

        if (!$program || !$student) {
            throw $this->createNotFoundException("Programme ou étudiant introuvable.");
        }

        // Supprimer l'étudiant du programme
        $program->removeEtudiant($student);

        // Mettre à jour la base de données
        $entityManager->flush();

        $this->addFlash('success', 'Étudiant supprimé du programme.');
        return $this->redirectToRoute('list_program_posts');
    }

    #[Route('/mentor/create_program', name: 'program_form')]
    public function createProg(Request $request): Response
    {
        $program = new Program();

        if ($this->userService->isLogin()) {
            $userTutorat = $this->getUser()->getUserTutorat();
            $program->setMentor($userTutorat);
        } else {
            $this->addFlash('warning', 'Vous devez vous connecter pour poster un programme de tutorat.');
            return $this->redirectToRoute('login');
        }

        $form = $this->createForm(ProgramType::class, $program);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Traitement du formulaire (sauvegarde de l'événement, etc.)
            $this->entityManager->persist($program);
            $this->entityManager->flush();

            $this->addFlash('success', 'Votre programme de tutorat a été créé avec succès !');

            return $this->redirectToRoute('tutorat');
        }


        return $this->render('tutorat/mentor/create_program.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/programs/update/{id}', name: 'update_program')]
    public function updateProgram(Request $request, EntityManagerInterface $entityManager, int $id): Response
    {
        $program = $entityManager->getRepository(Program::class)->find($id);

        if (!$program) {
            throw $this->createNotFoundException('Programme introuvable');
        }

        $form = $this->createForm(ProgramType::class, $program);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Le programme a été modifié.');
            return $this->redirectToRoute('list_program_posts');
        }

        return $this->render('tutorat/mentor/update_program.html.twig', [
            'programForm' => $form->createView(),
            'program' => $program,
        ]);
    }

    #[Route('/programs/delete/{id}', name: 'delete_program', methods: ['DELETE','POST'])]
    public function deleteProgram(EntityManagerInterface $entityManager, int $id): Response
    {
        $program = $entityManager->getRepository(Program::class)->find($id);

        if (!$program) {
            throw $this->createNotFoundException('Programme introuvable');
        }

        $entityManager->remove($program);
        $entityManager->flush();

        $this->addFlash('success', 'Le programme a été supprimé.');

        return $this->redirectToRoute('list_program_posts');
    }
}
