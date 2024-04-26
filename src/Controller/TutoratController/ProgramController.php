<?php

namespace App\Controller\TutoratController;

use App\Entity\Program;
use App\Form\ProgramType;
use App\Service\TutoratService\EtudiantService\EtudiantServiceInterface;
use App\Service\TutoratService\ProgramService\ProgramServiceInterface;
use App\Service\UserService\UserServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProgramController extends AbstractController
{
    private ProgramServiceInterface $programService;
    private EtudiantServiceInterface $etudiantService;
    private UserServiceInterface $userService;

    public function __construct(
        ProgramServiceInterface  $programService,
        EtudiantServiceInterface $etudiantService,
        UserServiceInterface     $userService
    )
    {
        $this->programService = $programService;
        $this->etudiantService = $etudiantService;
        $this->userService = $userService;
    }

    /**
     * Création des programmes de tutorat.
     *
     * @param Request $request La requête HTTP pour gérer le formulaire.
     *
     * @return Response La réponse HTTP.
     */
    #[Route('/mentor/create_program', name: 'program_form')]
    public function create(Request $request): Response
    {
        $program = new Program();

        if (!$this->userService->isLogin()) {
            $this->addFlash('warning', 'Vous devez vous connecter pour poster un programme de tutorat.');
            return $this->redirectToRoute('login');
        }

        $form = $this->programService->createProgramForm($request, $program);

        if ($form->isSubmitted() && $form->isValid()) {
            $program = $form->getData();

            // Assigner le mentor via le service
            $this->programService->assignMentorToProgram($program);

            $result = $this->programService->handleProgramFormSubmission($form, $program);

            if ($result['status'] === 'success') {
                $this->addFlash('success', $result['message']);
                return $this->redirectToRoute('tutorat');
            } else {
                $this->addFlash('error', 'Une erreur s\'est produite lors de la création du programme.');
            }
        }

        return $this->render('tutorat/mentor/create_program.html.twig', [
            'programForm' => $form->createView(),
        ]);
    }

    /**
     * Met à jour un programme de tutorat.
     *
     * @param int $id L'ID du programme à mettre à jour.
     *
     * @return Response La réponse HTTP, soit le rendu du formulaire, soit une redirection après soumission.
     */
    #[Route('/programs/update/{id}', name: 'update_program')]
    public function updateProgram(Request $request, EntityManagerInterface $entityManager, int $id): Response
    {
        $program = $this->programService->getProgramById($id);

        if (!$program) {
            throw $this->createNotFoundException('Programme introuvable');
        }

        $form = $this->createForm(ProgramType::class, $program);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->programService->saveProgram($program);
            $this->addFlash('success', 'Le programme a été modifié.');
            return $this->redirectToRoute('list_program_posts');
        }

        return $this->render('tutorat/mentor/update_program.html.twig', [
            'programForm' => $form->createView(),
            'program' => $program,
        ]);
    }


    /**
     * Supprime un programme de tutorat.
     *
     * @param int $id L'ID du programme à supprimer.
     *
     * @return Response La réponse HTTP, généralement une redirection après suppression réussie.
     */
    #[Route('/programs/delete/{id}', name: 'delete_program', methods: ['DELETE','POST'])]
    public function deleteProgram(int $id): Response
    {
        $program = $this->programService->getProgramById($id);

        if (!$program) {
            throw $this->createNotFoundException('Programme introuvable');
        }

        $this->programService->removeProgram($program);

        $this->addFlash('success', 'Le programme a été supprimé.');

        return $this->redirectToRoute('list_program_posts');
    }

    /**
     * Permet à un utilisateur de rejoindre un programme.
     *
     * @param int $id L'ID du programme à rejoindre.
     *
     * @return Response La réponse HTTP.
     */
    #[Route('/program/{id}', name: 'program_join', requirements: ['id' => '\d+'])]
    public function joinProgram(int $id): Response
    {
        $user = $this->getUser();
        $program = $this->programService->getProgramById($id);

        if (!$program) {
            throw $this->createNotFoundException('Programme introuvable');
        }

        // Utilisez le service pour vérifier si l'utilisateur est un étudiant
        if (!$this->etudiantService->isEtudiant($user)) {
            $this->addFlash('warning', 'Vous devez vous inscrire en tant qu\'étudiant.');
            return $this->redirectToRoute('registerEtudiant');
        }

        // Utilisez le service pour ajouter l'étudiant au programme
        $result = $this->programService->addEtudiantToProgram($user, $program);

        if ($result['status'] === 'success') {
            $this->addFlash('success', 'Vous êtes maintenant inscrit au programme.');
            return $this->redirectToRoute('tutorat');
        } else {
            $this->addFlash('warning', $result['message']);
            return $this->redirectToRoute('tutorat');
        }
    }

    /**
     * Permet à un étudiant de quitter un programme.
     *
     * @param int $id L'ID du programme à quitter.
     *
     * @return Response La réponse HTTP.
     */
    #[Route('/programs/quit/{id}', name: 'quit_program', methods: ['POST'])]
    public function quitProgram(int $id): Response
    {
        // Utilisez le service pour obtenir le programme par ID
        $program = $this->programService->getProgramById($id);

        if (!$program) {
            throw $this->createNotFoundException('Programme introuvable');
        }

        $user = $this->getUser();
        $userTutorat = $user->getUserTutorat();

        // Utilisez le service pour retirer l'étudiant du programme
        $this->programService->unregisterEtudiantFromProgram($userTutorat, $program);

        $this->addFlash('success', 'Vous avez quitté le programme.');
        return $this->redirectToRoute('list_program_posts');
    }

    /**
     * Supprime un étudiant d'un programme de tutorat.
     *
     * Cette méthode utilise les services pour récupérer le programme et l'étudiant par leurs ID respectifs.
     *
     * @param int $program_id L'ID du programme.
     * @param int $student_id L'ID de l'étudiant à supprimer du programme.
     *
     * @return Response La réponse HTTP, généralement une redirection après la suppression.
     */
    #[Route('/programs/{program_id}/remove-student/{student_id}', name: 'remove_student_from_program', methods: ['DELETE', 'POST'])]
    public function removeEtudiantFromProgram(int $program_id, int $student_id): Response
    {
        $program = $this->programService->getProgramById($program_id);
        $etudiant = $this->etudiantService->getEtudiantById($student_id);

        if (!$program || !$etudiant) {
            throw $this->createNotFoundException("Programme ou étudiant introuvable.");
        }

        $this->programService->unregisterEtudiantFromProgram($etudiant, $program);

        $this->addFlash('success', 'Étudiant supprimé du programme.');
        return $this->redirectToRoute('list_program_posts');
    }
}