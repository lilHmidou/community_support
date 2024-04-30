<?php

namespace App\Tests\Unit\Entity;
use App\Entity\EmailLog;
use App\Entity\User;
use PHPUnit\Framework\TestCase;


class EmailLogTest extends TestCase
{
    private EmailLog $emailLog;

    protected function setUp(): void
    {
        $this->emailLog = new EmailLog();
    }

    public function testInitialValues(): void
    {
        $this->assertNull($this->emailLog->getId());
        $this->assertNull($this->emailLog->getMessage());
        $this->assertNull($this->emailLog->getSender());
        $this->assertNull($this->emailLog->getRecipient());
    }

    public function testSetAndGetMessage(): void
    {
        $this->emailLog->setMessage("Hello, world!");
        $this->assertSame("Hello, world!", $this->emailLog->getMessage());
    }

    public function testSetAndGetSenderAndRecipient(): void
    {
        $sender = new User();
        $recipient = new User();

        $this->emailLog->setSender($sender);
        $this->assertSame($sender, $this->emailLog->getSender());

        $this->emailLog->setRecipient($recipient);
        $this->assertSame($recipient, $this->emailLog->getRecipient());
    }
}