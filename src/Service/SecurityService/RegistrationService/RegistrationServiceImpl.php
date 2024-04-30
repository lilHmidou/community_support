<?php

namespace App\Service\SecurityService\RegistrationService;

use App\Entity\User;
use App\Exception\EmailAlreadyExistsException;
use App\Exception\PasswordMismatchException;
use App\Form\UserForm\RegistrationType;
use App\Service\SecurityService\PasswordService\PasswordServiceImpl;
use App\Service\SecurityService\ValidateRegistrationService\ValidateRegistrationServiceInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

class RegistrationServiceImpl implements RegistrationServiceInterface
{
    private PasswordServiceImpl $passwordService;
    private FormFactoryInterface $formFactory;
    private ValidateRegistrationServiceInterface $validateRegistrationService;

    public function __construct(
        PasswordServiceImpl                     $passwordService,
        FormFactoryInterface                    $formFactory,
        ValidateRegistrationServiceInterface    $validateRegistrationService
    ) {
        $this->passwordService = $passwordService;
        $this->formFactory = $formFactory;
        $this->validateRegistrationService = $validateRegistrationService;
    }

    public function createRegistrationForm(User $user = null) : FormInterface
    {
        return $this->formFactory->create(RegistrationType::class, $user);
    }
    public function register(User $user, FormInterface $form): array
    {
        try {
            $this->validateRegistrationService->checkEmailExists($user);

            $errors = $this->validateRegistrationService->validateFormErrors($form);
            if (!empty($errors)) {
                return ['error' => $errors];
            }

            $this->validateRegistrationService->checkPasswordMatch($form, 'now');

            // Si tout est correct, procéder à la mise à jour du mot de passe et à l'inscription
            $passwordData = $this->passwordService->getPasswordData($form, 'now');
            $this->passwordService->updatePassword($user, $form, $passwordData, 'now');

            return ['success' => "Vous êtes maintenant inscrit! Bienvenue sur la plateforme."];

        } catch (EmailAlreadyExistsException | PasswordMismatchException $e) {
            // Capture d'exceptions spécifiques
            return ['error' => [$e->getMessage()]];

        } catch (\Exception $e) {
            // Gérer les exceptions inattendues
            return ['error' => ['Une erreur inattendue s\'est produite: ' . $e->getMessage()]];
        }
    }
}