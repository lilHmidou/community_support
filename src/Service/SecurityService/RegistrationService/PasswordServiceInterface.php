<?php

namespace App\Service\SecurityService\RegistrationService;

use App\Entity\User;
use Symfony\Component\Form\FormInterface;

interface PasswordServiceInterface
{
    public function getPasswordData(FormInterface $form): array;
    public function checkPasswordMatch(array $passwordData): bool;
    public function hashPassword(User $user, string $plainPassword): string;
}