<?php

namespace App\Service\SecurityService\ValidateRegistrationService;

use App\Entity\User;
use App\Exception\EmailAlreadyExistsException;
use App\Exception\PasswordMismatchException;
use App\Service\SecurityService\PasswordService\PasswordServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;

class ValidateRegistrationServiceImpl implements ValidateRegistrationServiceInterface
{
    private EntityManagerInterface $entityManager;
    private PasswordServiceInterface $passwordService;

    public function __construct(
        EntityManagerInterface      $entityManager,
        PasswordServiceInterface    $passwordService
    )
    {
        $this->entityManager = $entityManager;
        $this->passwordService = $passwordService;
    }

    public function checkEmailExists(User $user): void
    {
        $existingUser = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $user->getEmail()]);
        if ($existingUser) {
            throw new EmailAlreadyExistsException();
        }
    }

    public function validateFormErrors(FormInterface $form): array
    {
        $errors = [];
        foreach ($form->getErrors(true) as $error) {
            $errors[] = $error->getMessage();
        }
        return $errors;
    }

    public function checkPasswordMatch(FormInterface $form, string $type): void
    {
        $passwordData = $this->passwordService->getPasswordData($form, $type);

        if ($passwordData['password'] !== $passwordData['confirmPassword']) {
            throw new PasswordMismatchException('Les mots de passe ne sont pas identiques. Veuillez réessayer.');
        }
    }
}