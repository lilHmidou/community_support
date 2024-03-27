<?php

namespace App\Controller;

use App\Entity\Post;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LikeController extends AbstractController
{
    private $postRepository;

    public function __construct(PostRepository $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    #[Route('/list_liked_posts', name: 'list_liked_posts')]
    public function listLikedPosts(): Response
    {
        $user = $this->getUser();

        if (!$user) {
            throw $this->createNotFoundException('Utilisateur non trouvé.');
        }

        // Récupérer l'ID de l'utilisateur connecté
        $userId = $user->getId();

        // Récupérer tous les posts likés par l'utilisateur actuel
        $likedPosts = $this->postRepository->findPostsLikedByUser($userId);

        return $this->render('user/likes.html.twig', [
            'likedPosts' => $likedPosts,
        ]);
    }
}
