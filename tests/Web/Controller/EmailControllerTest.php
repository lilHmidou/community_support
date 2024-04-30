<?php

namespace App\Tests\Web\Controller;

use App\Controller\EmailController;
use App\Service\EmailService\EmailServiceImpl;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\User\UserInterface;

class EmailControllerTest extends WebTestCase
{
    private $client;
    private $emailServiceMock;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->emailServiceMock = $this->createMock(EmailServiceImpl::class);

        $this->client->getContainer()->set(EmailServiceImpl::class, $this->emailServiceMock);
    }

    public function testSendEmailUserNotLoggedIn(): void
    {
        $this->client->request('GET', '/send-email/1');
        $this->assertResponseRedirects('/login');
    }

    public function testParticipateUserNotLoggedIn(): void
    {
        $this->client->request('GET', '/participate/1');
        $this->assertResponseRedirects('/login');
    }
}

