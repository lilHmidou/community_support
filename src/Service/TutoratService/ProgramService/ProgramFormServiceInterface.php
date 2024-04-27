<?php

namespace App\Service\TutoratService\ProgramService;

use App\Entity\Program;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

interface ProgramFormServiceInterface
{
    public function createProgramForm(Request $request, ?Program $program = null): FormInterface;
    public function handleProgramFormSubmission(FormInterface $form): array;
}