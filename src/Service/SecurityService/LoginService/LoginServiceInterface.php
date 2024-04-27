<?php

namespace App\Service\SecurityService\LoginService;

use Symfony\Component\Security\Core\Exception\AuthenticationException;

interface LoginServiceInterface
{
    public function getLastLoginError() : AuthenticationException;
}