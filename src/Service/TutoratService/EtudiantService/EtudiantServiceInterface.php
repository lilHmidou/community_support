<?php

namespace App\Service\TutoratService\EtudiantService;

use App\Entity\Etudiant;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

interface EtudiantServiceInterface
{
    public function getEtudiantById(int $id): ?Etudiant;
    public function createEtudiantForm(Request $request): FormInterface;
    public function handleEtudiantFormSubmission(Form $form): array;
    public function isEtudiant($user): bool;
}