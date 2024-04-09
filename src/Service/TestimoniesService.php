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

    public function addTestimony(Testimonies $testimony): void
    {
        $this->entityManager->persist($testimony);
        $this->entityManager->flush();
    }

    // src/Service/TestimoniesService.php
    public function getAllTestimonies()
    {
        return $this->entityManager->getRepository(Testimonies::class)->findAll();
    }

}
