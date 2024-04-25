<?php

namespace App\Service\SecurityService\RegistrationService;

use App\Entity\User;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class PasswordServiceImpl implements PasswordServiceInterface
{
    private UserPasswordHasherInterface $passwordHashed;

    public function __construct(UserPasswordHasherInterface $passwordHashed)
    {
        $this->passwordHashed = $passwordHashed;
    }

    public function hashPassword(User $user, string $plainPassword): string
    {
        return $this->passwordHashed->hashPassword($user, $plainPassword);
    }

    public function getPasswordData(FormInterface $form): array
    {
        $plainPassword = $form->get('plainPassword')->getData();
        $confirmPassword = $form->get('confirmPassword')->getData();

        return [
            'plainPassword' => $plainPassword,
            'confirmPassword' => $confirmPassword,
        ];
    }

    public function checkPasswordMatch(array $passwordData): bool
    {
        return $passwordData['plainPassword'] === $passwordData['confirmPassword'];
    }


}