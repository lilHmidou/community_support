<?php

namespace App\Service\TutoratService\ProgramService;

use App\Entity\Program;
use App\Entity\Etudiant;
use App\Repository\ProgramRepository;
use Doctrine\ORM\EntityManagerInterface;

class ProgramServiceImpl implements ProgramServiceInterface
{
    private EntityManagerInterface $entityManager;
    private ProgramRepository $programRepository;

    public function __construct(
        EntityManagerInterface  $entityManager,
        ProgramRepository       $programRepository
    ) {
        $this->entityManager = $entityManager;
        $this->programRepository = $programRepository;
    }

    public function getProgramById(int $id): ?Program
    {
        return $this->programRepository->find($id);
    }

    public function isEtudiant($user): bool
    {
        $userTutorat = $user->getUserTutorat();
        return $userTutorat && $userTutorat instanceof Etudiant;
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

    public function removeEtudiantFromProgram(Etudiant $etudiant, Program $program): void
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