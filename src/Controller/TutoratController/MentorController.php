<?php

// src/Controller/TutoratController/MentorController.php
namespace App\Controller\TutoratController;

use App\Entity\Etudiant;
use App\Entity\Mentor;
use App\Entity\Program;
use App\Form\MentorType;
use App\security\Role;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\UserService;
use App\Service\FileUploadService;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

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

            // Ajouter le rôle "ROLE_ETUDIANT" à l'utilisateur connecté
            $user = $this->getUser();
            $user->addRole(Role::ROLE_MENTOR);
            $this->entityManager->persist($user);
            $this->entityManager->flush();

            $this->entityManager->persist($mentor);
            $this->entityManager->flush();

            $this->addFlash('success', 'Vous venez d\'être admis en tant que mentor. Veuillez vous reconnecter.');
            return $this->redirectToRoute('login');
        }

        return $this->render('tutorat/mentorForm.html.twig', [
            'mentorForm' => $form->createView(),
        ]);
    }
    #[Route('/programs/{program_id}/remove-student/{student_id}', name: 'remove_student_from_program', methods: ['DELETE', 'POST'])]
    public function removeStudentFromProgram(EntityManagerInterface $entityManager, int $program_id, int $student_id): Response
    {
        // Récupérer le programme
        $program = $entityManager->getRepository(Program::class)->find($program_id);

        // Récupérer l'étudiant
        $student = $entityManager->getRepository(Etudiant::class)->find($student_id);

        if (!$program || !$student) {
            throw $this->createNotFoundException("Programme ou étudiant introuvable.");
        }

        // Supprimer l'étudiant du programme
        $program->removeEtudiant($student);

        // Mettre à jour la base de données
        $entityManager->flush();

        // Retourner une réponse
        $this->addFlash('success', 'Étudiant supprimé du programme.');
        return $this->redirectToRoute('list_program_posts');
    }
}
