<?php

namespace App\Service\SecurityService\LoginService;

interface LoginServiceInterface
{
    public function getLastLoginError() : ?\Symfony\Component\Security\Core\Exception\AuthenticationException;
}