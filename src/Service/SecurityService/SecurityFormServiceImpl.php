<?php
namespace App\Service\SecurityService;

use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityFormServiceImpl implements SecurityFormServiceInterface
{
    private AuthenticationUtils $authenticationUtils;

    public function __construct(AuthenticationUtils $authenticationUtils)
    {
        $this->authenticationUtils = $authenticationUtils;
    }

    public function prepareSecurityForm(): array
    {
        $lastUsername = $this->authenticationUtils->getLastUsername();

        return [
            'last_username' => $lastUsername,
            'isCheckboxChecked' => false,
        ];
    }
}