<?php

namespace App\Service\LikeService;

use App\Entity\Post;
use App\Repository\PostLikeRepository;
use Symfony\Bundle\SecurityBundle\Security;

class LikeServiceImpl implements LikeServiceInterface
{

    private PostLikeRepository $postLikeRepository;
    private Security $security;

    public function __construct(PostLikeRepository $postLikeRepository, Security $security)
    {
        $this->postLikeRepository = $postLikeRepository;
        $this->security = $security;
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

}