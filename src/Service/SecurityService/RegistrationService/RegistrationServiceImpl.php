<?php

namespace App\Service\SecurityService\RegistrationService;

use App\Entity\User;
use App\Form\UserForm\RegistrationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

class RegistrationServiceImpl implements RegistrationServiceInterface
{
    private EntityManagerInterface $entityManager;
    private PasswordServiceImpl $passwordService;
    private FormFactoryInterface $formFactory;

    public function __construct(
        EntityManagerInterface $entityManager,
        PasswordServiceImpl    $passwordService,
        FormFactoryInterface   $formFactory
    ) {
        $this->entityManager = $entityManager;
        $this->passwordService = $passwordService;
        $this->formFactory = $formFactory;
    }

    public function createRegistrationForm(User $user = null) : \Symfony\Component\Form\FormInterface
    {
        $user = $user ?? new User();
        return $this->formFactory->create(RegistrationType::class, $user);
    }
    public function register(User $user, FormInterface $form): array
    {
        $errors = $this->validateRegistration($form, $user);

        // Si des erreurs existent, renvoyer immédiatement
        if (!empty($errors)) {
            return ['error' => $errors];
        }

        $passwordData = $this->passwordService->getPasswordData($form);

        // Hachage du mot de passe
        $hashedPassword = $this->passwordService->hashPassword($user, $passwordData['plainPassword']);
        $user->setPassword($hashedPassword);

        // Persistance de l'utilisateur
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return ['success' => "Vous êtes maintenant inscrit! Bienvenue sur la plateforme."];
    }

    public function validateRegistration(FormInterface $form, User $user): array
    {
        $errors = [];

        // Ordre de vérifications des erreurs
        // 1. Vérifier si l'e-mail existe déjà
        $existingUser = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $user->getEmail()]);
        if ($existingUser) {
            $errors[] = "L'adresse e-mail existe déjà.";
        }

        // 2. Vérifier les erreurs du formulaire (ajouté dans les contraintes du formulaire)
        foreach ($form->getErrors(true) as $error) {
            $errors[] = $error->getMessage();
        }

        // 3. Vérifiez si les mots de passe correspondent
        $passwordData = $this->passwordService->getPasswordData($form);
        if (!$this->passwordService->checkPasswordMatch($passwordData)) {
            $errors[] = 'Les mots de passe ne correspondent pas! Veuillez réessayer.';
        }

        return $errors;
    }
}