<?php

namespace App\Service\TutoratService\EtudiantService;

use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;

interface EtudiantServiceInterface
{
    public function createEtudiantForm(Request $request): Form;
    public function handleEtudiantFormSubmission(Form $form): array;
}