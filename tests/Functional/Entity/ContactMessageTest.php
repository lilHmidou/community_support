<?php

namespace App\Tests\Functional\Entity;

use App\Entity\ContactMessage;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ContactMessageTest extends KernelTestCase
{
    private $validator;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->validator = static::getContainer()->get('validator');
    }

    public function testInitialValues(): void
    {
        $contactMessage = new ContactMessage();
        $this->assertInstanceOf(\DateTimeImmutable::class, $contactMessage->getCreatedAtCM());
    }

    public function testSetAndGetTopic(): void
    {
        $contactMessage = new ContactMessage();
        $contactMessage->setTopic("Important");
        $this->assertSame("Important", $contactMessage->getTopic());
    }

    public function testValidationErrors(): void
    {
        $contactMessage = new ContactMessage();
        $contactMessage->setTopic(str_repeat('a', 31)); // 31 characters long, should be too long
        $contactMessage->setContentCM(""); // Empty content, should fail

        $violations = $this->validator->validate($contactMessage);
        $this->assertGreaterThan(0, count($violations), "Should have validation errors for topic length and blank content.");
    }

    public function testUserRelationship(): void
    {
        $user = new User();
        $contactMessage = new ContactMessage();
        $contactMessage->setUser($user);
        $this->assertSame($user, $contactMessage->getUser());
    }
}
