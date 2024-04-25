<?php

namespace App\Service\SecurityService\RegistrationService;

use App\Entity\User;
use Symfony\Component\Form\FormInterface;

interface RegistrationServiceInterface
{
    public function createRegistrationForm(User $user): \Symfony\Component\Form\FormInterface;

    public function register(User $user, FormInterface $form): array;
    public function validateRegistration(FormInterface $form, User $user): array;
}