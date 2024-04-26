<?php

namespace App\Tests\Functional\Services;

use App\Entity\User;
use App\Service\SecurityService\RegistrationService\RegistrationServiceImpl;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Form\FormInterface;

class RegistrationServiceImplTest extends KernelTestCase
{
    private $registrationService;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->registrationService = static::getContainer()->get(RegistrationServiceImpl::class);
    }

    public function testCreateRegistrationForm(): void
    {
        $user = new User();
        $form = $this->registrationService->createRegistrationForm($user);

        $this->assertInstanceOf(FormInterface::class, $form);
        $this->assertSame($user, $form->getData());
    }

}