<?php

namespace App\Service\SecurityService\PasswordService;

use App\Entity\User;
use Symfony\Component\Form\FormInterface;

interface PasswordServiceInterface
{
    public function getPasswordData(FormInterface $form, string $type): array;
    public function hashPassword(User $user, string $plainPassword): string;
    public function updatePassword(User $user, FormInterface $form, array $passwordData, string $type): void;
}