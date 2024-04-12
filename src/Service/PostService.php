<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;

class PostService
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getUserEmailByPostId(int $postId): ?string
    {
        $query = $this->entityManager->createQuery(
            'SELECT u.email
            FROM App\Entity\Post p
            JOIN App\Entity\User u WITH p.user = u.id
            WHERE p.id = :postId'
        )->setParameter('postId', $postId);

        return $query->getOneOrNullResult();
    }

}