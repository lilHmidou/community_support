<?php

namespace App\Service\TutoratService\MentorService;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

interface MentorServiceInterface
{
    public function createMentorForm(Request $request): FormInterface;
    public function handleMentorFormSubmission(FormInterface $form): array;
}