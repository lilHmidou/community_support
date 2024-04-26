<?php

namespace App\Service\TutoratService\ProgramService;

use App\Entity\Mentor;
use App\Entity\Program;
use App\Entity\Etudiant;
use App\Form\ProgramType;
use App\Repository\ProgramRepository;
use App\Service\UserService\UserServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

class ProgramServiceImpl implements ProgramServiceInterface
{
    private EntityManagerInterface $entityManager;
    private ProgramRepository $programRepository;
    private FormFactoryInterface $formFactory;
    private UserServiceInterface $userService;

    public function __construct(
        EntityManagerInterface  $entityManager,
        ProgramRepository       $programRepository,
        FormFactoryInterface    $formFactory,
        UserServiceInterface     $userService
    ) {
        $this->entityManager = $entityManager;
        $this->programRepository = $programRepository;
        $this->formFactory = $formFactory;
        $this->userService = $userService;
    }

    public function getProgramById(int $id): ?Program
    {
        return $this->programRepository->find($id);
    }

    public function createProgramForm(Request $request, ?Program $program = null): FormInterface
    {
        $program = $program ?? new Program();
        $form = $this->formFactory->create(ProgramType::class, $program);
        $form->handleRequest($request);
        return $form;
    }

    public function handleProgramFormSubmission(FormInterface $form): array
    {
        $program = $form->getData();

        $this->entityManager->persist($program);
        $this->entityManager->flush();

        return ['status' => 'success', 'message' => 'Programme créé avec succès'];
    }

    public function saveProgram(Program $program): void
    {
        $this->entityManager->flush();
    }

    public function removeProgram(Program $program): void
    {
        $this->entityManager->remove($program);
        $this->entityManager->flush();
    }

    public function assignMentorToProgram(Program $program): void
    {
        $user = $this->userService->getUser(); // Récupérez l'utilisateur connecté
        $mentor = $user->getUserTutorat(); // Récupérez le mentor associé à l'utilisateur

        if ($mentor instanceof Mentor) {
            $program->setMentor($mentor); // Assignation du mentor au programme
        } else {
            throw new \LogicException("L'utilisateur n'est pas un mentor."); // Gérer le cas où l'utilisateur n'est pas un mentor
        }
    }

    public function addEtudiantToProgram($user, Program $program): array
    {
        $userTutorat = $user->getUserTutorat();

        if ($this->isEtudiantInProgram($userTutorat, $program)) {
            return [
                'status' => 'warning',
                'message' => 'Vous participez déjà à ce programme.'
            ];
        }

        $program->addEtudiant($userTutorat);
        $this->entityManager->flush();

        return [
            'status' => 'success',
            'message' => 'Vous êtes maintenant inscrit au programme.'
        ];
    }

    public function unregisterEtudiantFromProgram(Etudiant $etudiant, Program $program): void
    {
        if ($program->getEtudiants()->contains($etudiant)) {
            $program->removeEtudiant($etudiant);
            $this->entityManager->flush();
        }
    }

    public function isEtudiantInProgram(Etudiant $etudiant, Program $program): bool
    {
        $etudiantIds = $program->getEtudiants()->map(
            fn ($e) => $e->getId()
        );

        return $etudiantIds->contains($etudiant->getId());
    }
}