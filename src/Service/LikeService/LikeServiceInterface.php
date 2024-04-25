<?php

namespace App\Service\LikeService;

use App\Entity\Post;
use App\Entity\User;

interface LikeServiceInterface
{
    public function checkLike(Post $post): bool;
    public function addPostLike(Post $post, User $user): void;
    public function deletePostLike(Post $post, User $user): void;
    public function getPostsLikedByUser(User $user): array;
}