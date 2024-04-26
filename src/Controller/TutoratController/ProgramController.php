<?php

namespace App\Controller\TutoratController;

use App\Service\TutoratService\ProgramService\ProgramServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProgramController extends AbstractController
{
    private ProgramServiceInterface $programService;

    public function __construct(ProgramServiceInterface $programService)
    {
        $this->programService = $programService;
    }

    #[Route('/program/{id}', name: 'program_join', requirements: ['id' => '\d+'])]
    public function joinProgram(int $id): Response
    {
        $user = $this->getUser();
        $program = $this->programService->getProgramById($id);

        if (!$program) {
            throw $this->createNotFoundException('Programme introuvable');
        }

        // Utilisez le service pour vérifier si l'utilisateur est un étudiant
        if (!$this->programService->isEtudiant($user)) {
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
        $this->programService->removeEtudiantFromProgram($userTutorat, $program);

        $this->addFlash('success', 'Vous avez quitté le programme.');
        return $this->redirectToRoute('list_program_posts');
    }
}