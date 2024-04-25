<?php

namespace App\Service\LikeService;

use App\Entity\Post;
use App\Entity\PostLike;
use App\Entity\User;
use App\Repository\PostLikeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;

class LikeServiceImpl implements LikeServiceInterface
{
    private PostLikeRepository $postLikeRepository;
    private Security $security;
    private EntityManagerInterface $entityManager;

    public function __construct(
        PostLikeRepository $postLikeRepository,
        Security $security,
        EntityManagerInterface $entityManager
    )
    {
        $this->postLikeRepository = $postLikeRepository;
        $this->security = $security;
        $this->entityManager = $entityManager;
    }

    public function checkLike(Post $post): bool
    {
        $user = $this->security->getUser();

        if (!$user) {
            return false;
        }

        $like = $this->postLikeRepository->findOneBy([
            'user_id' => $user,
            'post_id' => $post,
        ]);

        return $like !== null;
    }

    public function addPostLike(Post $post, User $user): void
    {
        $currentLikes = $post->getLike();
        $post->setLike($currentLikes + 1);
        $like = new PostLike();
        $like->setPostId($post->getId());
        $like->setUserId($user->getId());

        $this->entityManager->persist($like);
        $this->entityManager->flush();
    }

    public function deletePostLike(Post $post, User $user): void
    {
        $like = $this->postLikeRepository->findOneBy([
            'user_id' => $user,
            'post_id' => $post,
        ]);

        if ($like) {
            $currentLikes = $post->getLike();
            $post->setLike($currentLikes - 1);

            $this->entityManager->remove($like);
            $this->entityManager->flush();
        }
    }

    public function getPostsLikedByUser(User $user): array
    {
        return $this->postLikeRepository->findPostsLikedByUser($user->getId());
    }
}