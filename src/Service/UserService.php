<?php

// src/Service/UserService.php
namespace App\Service;

use Symfony\Bundle\SecurityBundle\Security;

class UserService
{
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function isLogin(): bool
    {
        return null !== $this->security->getUser();
    }
}
