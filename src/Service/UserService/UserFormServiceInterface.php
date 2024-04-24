<?php

namespace App\Service\UserService;

use App\Entity\User;

interface UserFormServiceInterface
{
    public function createRegistrationForm(User $user): \Symfony\Component\Form\FormInterface;

    public function prepareUserForm():array;

    public function getErrors(): ?\Symfony\Component\Security\Core\Exception\AuthenticationException;
}

?>