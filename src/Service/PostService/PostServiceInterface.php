<?php

namespace App\Service\PostService;

use App\Entity\Post;

interface PostServiceInterface
{
    public function getUserEmailByPostId(int $postId): ?string;
    public function getPostById(int $postId): ?Post;
    public function createPost(Post $post): void;
    public function updatePost(Post $post): void;
    public function deletePost(Post $post): void;
    public function findAllPostsByUser($user): array;
}