<?php

namespace App\Controller\TutoratController\ProgramController;

use App\Entity\Program;
use App\Form\ProgramType;
use App\Service\TutoratService\ProgramService\ProgramFormServiceInterface;
use App\Service\TutoratService\ProgramService\ProgramManagementServiceInterface;
use App\Service\UserService\UserServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProgramController extends AbstractController
{
    private ProgramManagementServiceInterface $programManagementService;
    private ProgramFormServiceInterface $programFormService;
    private UserServiceInterface $userService;

    public function __construct(
        ProgramManagementServiceInterface  $programManagementService,
        ProgramFormServiceInterface $programFormService,
        UserServiceInterface     $userService
    )
    {
        $this->programManagementService = $programManagementService;
        $this->programFormService = $programFormService;
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

        $form = $this->programFormService->createProgramForm($request, $program);

        if ($form->isSubmitted() && $form->isValid()) {
            $program = $form->getData();

            // Assigner le mentor via le service
            $this->programManagementService->assignMentorToProgram($program);

            $result = $this->programFormService->handleProgramFormSubmission($form, $program);

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
        $program = $this->programManagementService->getProgramById($id);

        if (!$program) {
            throw $this->createNotFoundException('Programme introuvable');
        }

        $form = $this->createForm(ProgramType::class, $program);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->programManagementService->saveProgram($program);
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
        $program = $this->programManagementService->getProgramById($id);

        if (!$program) {
            throw $this->createNotFoundException('Programme introuvable');
        }

        $this->programManagementService->removeProgram($program);

        $this->addFlash('success', 'Le programme a été supprimé.');

        return $this->redirectToRoute('list_program_posts');
    }
}