<?php

namespace App\Service\TutoratService\ProgramService;

use App\Entity\Etudiant;
use App\Entity\Program;

interface ProgramServiceInterface
{
    public function getProgramById(int $id): ?Program;
    public function isEtudiant($user): bool;
    public function addEtudiantToProgram($user, Program $program): array;
    public function removeEtudiantFromProgram(Etudiant $etudiant, Program $program): void;
    public function isEtudiantInProgram(Etudiant $etudiant, Program $program): bool;
}