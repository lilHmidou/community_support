<?php

namespace App\Service\TutoratService\ProgramService;

use App\Entity\Etudiant;
use App\Entity\Program;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

interface ProgramServiceInterface
{
    public function getProgramById(int $id): ?Program;
    public function createProgramForm(Request $request, ?Program $program = null): FormInterface;
    public function handleProgramFormSubmission(FormInterface $form): array;
    public function saveProgram(Program $program): void;
    public function removeProgram(Program $program): void;
    public function assignMentorToProgram(Program $program): void;
    public function addEtudiantToProgram($user, Program $program): array;
    public function unregisterEtudiantFromProgram(Etudiant $etudiant, Program $program): void;
    public function isEtudiantInProgram(Etudiant $etudiant, Program $program): bool;
}