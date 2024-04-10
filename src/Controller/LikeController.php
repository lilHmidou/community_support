<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\PostLike;
use App\Repository\PostLikeRepository;
use App\Repository\PostRepository;
use App\Service\LikeService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LikeController extends AbstractController
{
    private PostRepository $postRepository;
    private Security $security;
    private EntityManagerInterface $entityManager;

    public function __construct(PostRepository $postRepository, Security $security, EntityManagerInterface $entityManager)
    {
        $this->postRepository = $postRepository;
        $this->security = $security;
        $this->entityManager = $entityManager;
    }

    #[Route('/add_like/{id}', name: 'add_like')]
    public function addLike(Post $post): JsonResponse
    {
        $user = $this->security->getUser();
        if($user){
            $currentLikes = $post->getLike();
            $post->setLike($currentLikes + 1);
            $like = new PostLike();
            $like->setPostId($post->getId());
            $like->setUserId($user->getId());

            $this->entityManager->persist($like);

            $this->entityManager->flush();
        } else {
            //$this->addFlash('warning', 'Vous devez vous connecter pour liker un événement.');
        }

        return new JsonResponse(['likes' => $post->getLike()]);
    }

    #[Route('/remove_like/{id}', name: 'remove_like')]
    public function removeLike(Post $post, PostLikeRepository $postLikeRepository): JsonResponse
    {
        $currentLikes = $post->getLike();
        $post->setLike($currentLikes - 1);
        $user = $this->security->getUser();

        $post_id = $post->getId();
        $user_id = $user->getId();

        $postLikeRepository->removeLike($user_id, $post_id);

        $this->entityManager->flush();

        return new JsonResponse(['likes' => $post->getLike()]);
    }

    #[Route('/check_like/{id}', name: 'check_like')]
    public function checkLike(Post $post, LikeService $likeService): Response
    {
        $liked = $likeService->checkLike($post);

        // Rendre le template Twig en passant la variable $liked
        return $this->render('solidarity/index.html.twig', [
            'liked' => $liked,
        ]);
    }

    #[Route('/list_liked_posts', name: 'list_liked_posts')]
    public function listLikedPosts(PostLikeRepository $postLikeRepository): Response
    {
        $user = $this->getUser();

        if (!$user) {
            throw $this->createNotFoundException('Utilisateur non trouvé.');
        }

        // Récupérer l'ID de l'utilisateur connecté
        $userId = $user->getId();

        // Récupérer tous les posts likés par l'utilisateur actuel
        $likedPosts = $postLikeRepository->findPostsLikedByUser($userId);

        return $this->render('user/likes.html.twig', [
            'likedPosts' => $likedPosts,
        ]);
    }
}
