<?php

namespace App\Service\UserService\UserProfilService;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

interface UserProfileServiceInterface
{
    public function createProfilForm(): FormInterface;
    public function updateUserProfile(): void;
    public function deleteUserProfile(TokenStorageInterface $tokenStorage): void;
}