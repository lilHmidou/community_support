<?php

namespace App\Service\UserService;

use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class UserServiceImpl implements UserServiceInterface
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

    public function getUser(): UserInterface
    {
        return $this->security->getUser();
    }
}