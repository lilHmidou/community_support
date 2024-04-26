<?php

namespace App\Tests\Unit\Service;

use App\Service\SecurityService\SecurityFormServiceImpl;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use PHPUnit\Framework\TestCase;

class SecurityFormServiceImplTest extends TestCase
{
    private $securityFormService;
    private $authenticationUtilsMock;

    protected function setUp(): void
    {
        // Créer le mock pour AuthenticationUtils
        $this->authenticationUtilsMock = $this->createMock(AuthenticationUtils::class);

        // Instancier le service avec le mock
        $this->securityFormService = new SecurityFormServiceImpl($this->authenticationUtilsMock);
    }

    public function testPrepareSecurityForm(): void
    {
        // Configurer le mock pour retourner une valeur spécifique
        $this->authenticationUtilsMock->method('getLastUsername')
            ->willReturn('testuser');

        // Appeler la méthode à tester
        $result = $this->securityFormService->prepareSecurityForm();

        // Assert que le tableau retourné est correct
        $expected = [
            'last_username' => 'testuser',
            'isCheckboxChecked' => false,
        ];

        $this->assertEquals($expected, $result);
    }
}
