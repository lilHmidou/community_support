<?php
// src/Service/ContactServiceImpl.php
namespace App\Service\ContactService;

use App\Entity\ContactMessage;
use App\Service\UserService\UserServiceInterface;
use Doctrine\ORM\EntityManagerInterface;

class ContactServiceImpl implements ContactServiceInterface
{
    private EntityManagerInterface $entityManager;
    private UserServiceInterface $userService;

    public function __construct(
        EntityManagerInterface $entityManager,
        UserServiceInterface $userService
    )
    {
        $this->entityManager = $entityManager;
        $this->userService = $userService;
    }

    public function createContactMessage(): ContactMessage
    {
        $contactMessage = new ContactMessage();

        $user = $this->userService->getUser();
        $contactMessage->setUser($user);

        return $contactMessage;
    }

    public function saveContactMessage(ContactMessage $message): void
    {
        $this->entityManager->persist($message);
        $this->entityManager->flush();
    }
}