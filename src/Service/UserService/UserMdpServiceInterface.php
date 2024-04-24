<?php

namespace App\Service\UserService;

use App\Entity\User;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

interface UserMdpServiceInterface
{
    public function getPasswordData(FormInterface $form, Request $request): array;
    public function checkPasswordMatch(array $passwordData): bool;
    public function hashPassword(User $user, string $plainPassword): string;
}
?>