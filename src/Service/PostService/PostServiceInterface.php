<?php

namespace App\Service\PostService;

interface PostServiceInterface
{
    public function getUserEmailByPostId(int $postId): ?string;
}