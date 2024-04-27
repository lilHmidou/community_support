<?php

namespace App\Controller\TutoratController\ProgramController;

use App\Service\TutoratService\EtudiantService\EtudiantServiceInterface;
use App\Service\TutoratService\ProgramService\ProgramManagementServiceInterface;
use App\Service\TutoratService\ProgramService\ProgramParticipationServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProgramParticipationControler extends AbstractController
{
    private ProgramManagementServiceInterface $programManagementService;
    private ProgramParticipationServiceInterface $programParticipationService;
    private EtudiantServiceInterface $etudiantService;

    public function __construct(
        ProgramManagementServiceInterface $programManagementService,
        ProgramParticipationServiceInterface $programParticipationService,
        EtudiantServiceInterface $etudiantService
    )
    {
        $this->programManagementService = $programManagementService;
        $this->programParticipationService = $programParticipationService;
        $this->etudiantService = $etudiantService;
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
        $program = $this->programManagementService->getProgramById($id);

        if (!$program) {
            throw $this->createNotFoundException('Programme introuvable');
        }

        // Utilisez le service pour vérifier si l'utilisateur est un étudiant
        if (!$this->etudiantService->isEtudiant($user)) {
            $this->addFlash('warning', 'Vous devez vous inscrire en tant qu\'étudiant.');
            return $this->redirectToRoute('registerEtudiant');
        }

        // Utilisez le service pour ajouter l'étudiant au programme
        $result = $this->programParticipationService->addEtudiantToProgram($user, $program);

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
        $program = $this->programManagementService->getProgramById($id);

        if (!$program) {
            throw $this->createNotFoundException('Programme introuvable');
        }

        $user = $this->getUser();
        $userTutorat = $user->getUserTutorat();

        // Utilisez le service pour retirer l'étudiant du programme
        $this->programParticipationService->unregisterEtudiantFromProgram($userTutorat, $program);

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
        $program = $this->programManagementService->getProgramById($program_id);
        $etudiant = $this->etudiantService->getEtudiantById($student_id);

        if (!$program || !$etudiant) {
            throw $this->createNotFoundException("Programme ou étudiant introuvable.");
        }

        $this->programParticipationService->unregisterEtudiantFromProgram($etudiant, $program);

        $this->addFlash('success', 'Étudiant supprimé du programme.');
        return $this->redirectToRoute('list_program_posts');
    }
}