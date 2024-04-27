<?php

namespace App\Service\SecurityService\ValidateRegistrationService;

use App\Entity\User;
use Symfony\Component\Form\FormInterface;

interface ValidateRegistrationServiceInterface
{
    public function checkEmailExists(User $user): void;
    public function validateFormErrors(FormInterface $form): array;
    public function checkPasswordMatch(FormInterface $form, string $type): void;
}