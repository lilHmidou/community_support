<?php
namespace App\Service\SecurityService;

use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityFormServiceImpl implements SecurityFormServiceInterface
{
    private $authenticationUtils;

    public function __construct( AuthenticationUtils $authenticationUtils)
    {
        $this->authenticationUtils = $authenticationUtils;
    }

    public function prepareSecurityForm(): array
    {
        $lastUsername = $this->authenticationUtils->getLastUsername();
        $isCheckboxChecked= false;

        $data = [
            'last_username' => $lastUsername,
            'isCheckboxChecked' => $isCheckboxChecked,
        ];

        return $data;
    }
}