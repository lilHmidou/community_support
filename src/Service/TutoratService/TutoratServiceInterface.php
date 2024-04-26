<?php

namespace App\Service\TutoratService;

use App\Entity\User;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

interface TutoratServiceInterface
{
    public function createFormBasedOnRole(FormFactoryInterface $formFactory, User $user): FormInterface;
}