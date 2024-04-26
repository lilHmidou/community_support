<?php

namespace App\Service\TestimoniesService;

use App\Entity\Testimonies;
use Doctrine\ORM\EntityManagerInterface;

class TestimoniesServiceImpl implements TestimoniesServiceInterface
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
    public function getAllTestimonies()
    {
        return $this->entityManager->getRepository(Testimonies::class)->findAll();
    }
}
