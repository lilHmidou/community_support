<?php
// src/Service/ContactServiceImpl.php
namespace App\Service\ContactService;

use App\Entity\ContactMessage;
use Doctrine\ORM\EntityManagerInterface;

class ContactServiceImpl implements ContactServiceInterface
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
