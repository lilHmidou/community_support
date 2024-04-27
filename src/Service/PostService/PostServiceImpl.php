<?php

namespace App\Service\PostService;

use App\Entity\Post;
use App\Service\UserService\UserServiceInterface;
use Doctrine\ORM\EntityManagerInterface;

class PostServiceImpl implements PostServiceInterface
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

    public function getPostById(int $postId): ?Post
    {
        return $this->entityManager->getRepository(Post::class)->find($postId);
    }

    public function createPost(Post $post): void
    {
        if (!$this->userService->isLogin()) {
            throw new \LogicException('L\'utilisateur doit être connecté pour créer un post.');
        }

        $post->setUser($this->userService->getUser());
        $post->setLike(0);

        $this->entityManager->persist($post);
        $this->entityManager->flush();
    }

    public function updatePost(Post $post): void
    {
        $this->entityManager->flush();
    }

    public function deletePost(Post $post): void
    {
        $this->entityManager->remove($post);
        $this->entityManager->flush();
    }

    public function findAllPostsByUser($user): array
    {
        return $this->entityManager->getRepository(Post::class)->findBy(['user' => $user]);
    }
}