<?php

namespace App\Service\TutoratService\ProgramService\ProgramManagementService;

use App\Entity\Mentor;
use App\Entity\Program;
use App\Entity\User;
use App\Repository\ProgramRepository;
use App\Service\UserService\UserServiceInterface;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;

class ProgramManagementServiceImpl implements ProgramManagementServiceInterface
{
    private EntityManagerInterface $entityManager;
    private ProgramRepository $programRepository;
    private UserServiceInterface $userService;

    public function __construct(
        EntityManagerInterface  $entityManager,
        ProgramRepository       $programRepository,
        UserServiceInterface    $userService
    ) {
        $this->entityManager = $entityManager;
        $this->programRepository = $programRepository;
        $this->userService = $userService;
    }

    public function getProgramById(int $id): ?Program
    {
        return $this->programRepository->find($id);
    }

    public function getUserPrograms(User $user): ?Collection
    {
        if ($user->hasRole('ROLE_MENTOR')) {
            $programs = $user->getUserTutorat()->getMentorPrograms();
        } elseif ($user->hasRole('ROLE_ETUDIANT')) {
            $programs = $user->getUserTutorat()->getEtudiantPrograms();
        }

        return null;
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
        $user = $this->userService->getUser();
        $mentor = $user->getUserTutorat();

        if ($mentor instanceof Mentor) {
            $program->setMentor($mentor);
        } else {
            throw new \LogicException("L'utilisateur n'est pas un mentor.");
        }
    }

    public function removeMentorPrograms(Mentor $mentor): void
    {
        $programs = $mentor->getMentorPrograms();

        foreach ($programs as $program) {
            $this->removeProgram($program);
        }
    }
}