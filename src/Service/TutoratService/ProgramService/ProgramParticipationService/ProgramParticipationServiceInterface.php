<?php

namespace App\Service\TutoratService\ProgramService\ProgramParticipationService;

use App\Entity\Etudiant;
use App\Entity\Program;

interface ProgramParticipationServiceInterface
{
    public function addEtudiantToProgram($user, Program $program): array;
    public function unregisterEtudiantFromProgram(Etudiant $etudiant, Program $program): void;
    public function isEtudiantInProgram(Etudiant $etudiant, Program $program): bool;
}