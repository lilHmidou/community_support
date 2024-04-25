<?php

namespace App\Service\SecurityService\LoginService;

use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginServiceImpl implements LoginServiceInterface
{
    private AuthenticationUtils $authenticationUtils;

    public function __construct(
        AuthenticationUtils $authenticationUtils
    ) {
        $this->authenticationUtils = $authenticationUtils;
    }

    public function getLastLoginError() : ?\Symfony\Component\Security\Core\Exception\AuthenticationException
    {
        return $this->authenticationUtils->getLastAuthenticationError();
    }
}