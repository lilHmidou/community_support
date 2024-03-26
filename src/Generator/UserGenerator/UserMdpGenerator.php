<?php

namespace App\Generator\UserGenerator;

use App\Entity\User;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserMdpGenerator
{
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function hashPassword(User $user, string $plainPassword): string
    {
        return $this->passwordHasher->hashPassword($user, $plainPassword);
    }

    public function getPasswordData(FormInterface $form, Request $request): array
    {
        $plainPassword = $form->get('plainPassword')->getData();
        $confirmPassword = $request->request->get('confirmPassword');

        return [
            'plainPassword' => $plainPassword,
            'confirmPassword' => $confirmPassword,
        ];
    }

    /**
     * Vérifie si les deux mots de passe fournis dans le tableau correspondent.
     *
     * @param array $passwordData Tableau contenant 'plainPassword' et 'confirmPassword'.
     * @return bool Retourne true si les mots de passe correspondent, sinon false.
     */
    public function checkPasswordMatch(array $passwordData): bool
    {
        return $passwordData['plainPassword'] === $passwordData['confirmPassword'];
    }


}