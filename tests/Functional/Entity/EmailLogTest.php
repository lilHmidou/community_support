<?php

namespace App\Tests\Functional\Entity;

use App\Entity\EmailLog;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class EmailLogTest extends KernelTestCase
{
    private $validator;

    protected function setUp(): void
    {
        // Boot the Symfony kernel
        self::bootKernel();
        // Retrieve the validator service from the container
        $this->validator = static::getContainer()->get('validator');
    }

    public function testEmailValidation(): void
    {
        $emailLog = new EmailLog();
        $emailLog->setSenderEmail("invalid_email");
        $emailLog->setRecipientEmail("valid@example.com");

        $violations = $this->validator->validate($emailLog);
        $this->assertGreaterThan(0, count($violations), "Le test devrait trouver des violations pour un email invalide.");
    }
}
