<?php
// src/Service/ContactService.php
namespace App\Service;

use App\Entity\ContactMessage;
use Doctrine\ORM\EntityManagerInterface;

class ContactService
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function saveContactMessage(ContactMessage $message): void
    {
        $this->entityManager->persist($message);
        $this->entityManager->flush();
    }
}
