<?php

namespace App\Service\SecurityService\PasswordService;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class PasswordServiceImpl implements PasswordServiceInterface
{
    private UserPasswordHasherInterface $passwordHashed;
    private EntityManagerInterface $entityManager;

    public function __construct(
        UserPasswordHasherInterface $passwordHashed,
        EntityManagerInterface      $entityManager
    )
    {
        $this->passwordHashed = $passwordHashed;
        $this->entityManager = $entityManager;
    }

    public function getPasswordData(FormInterface $form, string $type): array
    {
        $passwordKey = $type === 'new' ? 'newPassword' : 'plainPassword';
        $confirmPasswordKey = $type === 'new' ? 'confirmNewPassword' : 'confirmPassword';

        if (!$form->has($passwordKey) || !$form->has($confirmPasswordKey)) {
            throw new \InvalidArgumentException("Le formulaire ne contient pas les champs requis.");
        }

        $plainPassword = $form->get($passwordKey)->getData();
        $confirmPassword = $form->get($confirmPasswordKey)->getData();


        if ($plainPassword === null || $confirmPassword === null) {
            throw new \InvalidArgumentException("Les champs du mot de passe ne peuvent pas être vides.");
        }

        return [
            'password' => $plainPassword,
            'confirmPassword' => $confirmPassword,
        ];
    }

    public function hashPassword(User $user, string $plainPassword): string
    {
        return $this->passwordHashed->hashPassword($user, $plainPassword);
    }

    public function updatePassword(User $user, FormInterface $form, array $passwordData, string $type): void
    {
        $hashedPassword = $this->hashPassword($user, $passwordData['password']);
        $user->setPassword($hashedPassword);
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
}