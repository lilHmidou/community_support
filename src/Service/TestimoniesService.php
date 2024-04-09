<?php

// src/Service/TestimoniesService.php
namespace App\Service;

use App\Entity\Testimonies;
use Doctrine\ORM\EntityManagerInterface;

class TestimoniesService
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function addTestimony(Testimonies $testimony)
    {
        $this->entityManager->persist($testimony);
        $this->entityManager->flush();
    }
}
