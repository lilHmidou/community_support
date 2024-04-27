<?php

namespace App\Service\SecurityService\RegistrationService;

use App\Entity\User;
use Symfony\Component\Form\FormInterface;

interface RegistrationServiceInterface
{
    public function createRegistrationForm(User $user): FormInterface;
    public function register(User $user, FormInterface $form): array;
}