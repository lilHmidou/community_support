<?php

namespace App\Service\TutoratService\ProgramService;

use App\Entity\Etudiant;
use App\Entity\Program;
use Doctrine\ORM\EntityManagerInterface;

class ProgramParticipationServiceImpl
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
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