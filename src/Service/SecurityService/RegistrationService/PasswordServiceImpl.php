<?php

namespace App\Service\SecurityService\RegistrationService;

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
        EntityManagerInterface $entityManager
    )
    {
        $this->passwordHashed = $passwordHashed;
        $this->entityManager = $entityManager;
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

    public function getNewPasswordData(FormInterface $form): array
    {
        $plainPassword = $form->get('newPassword')->getData();
        $confirmPassword = $form->get('confirmNewPassword')->getData();

        return [
            'newPassword' => $plainPassword,
            'confirmNewPassword' => $confirmPassword,
        ];
    }

    public function checkPasswordMatch(array $passwordData, string $type): bool
    {
        if($type === 'new')
        {
            return $passwordData['newPassword'] === $passwordData['confirmNewPassword'];
        }
        return $passwordData['plainPassword'] === $passwordData['confirmPassword'];
    }

    public function updatePassword(User $user, array $passwordData): void
    {
        if (!$this->checkPasswordMatch($passwordData, 'new')) {
            throw new \RuntimeException('Les mots de passe ne sont pas identiques ! Veuillez réessayer.');
        }

        $hashedPassword = $this->hashPassword($user, $passwordData['newPassword']);
        $user->setPassword($hashedPassword);
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
}