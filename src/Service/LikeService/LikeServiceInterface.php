<?php

namespace App\Service\LikeService;

use App\Entity\Post;

interface LikeServiceInterface
{
    public function checkLike(Post $post): bool;
}