<?php

namespace App\Controller;

use App\Entity\Post;
use App\Service\LikeService\LikeServiceInterface;
use App\Service\UserService\UserServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LikeController extends AbstractController
{
    private UserServiceInterface $userService;
    private LikeServiceInterface $likeService;

    public function __construct(
        UserServiceInterface $userService,
        LikeServiceInterface $likeService,
    )
    {
        $this->userService = $userService;
        $this->likeService = $likeService;
    }

    /**
     * Ajoute un like à un post.
     *
     * @param Post $post Le post à liker.
     *
     * @return JsonResponse La réponse JSON contenant le nombre de likes.
     */
    #[Route('/add_like/{id}', name: 'add_like')]
    public function addLike(Post $post): JsonResponse
    {
        if($this->userService->isLogin()) {
            $user = $this->userService->getUser();
            $this->likeService->addPostLike($post, $user);
        } else {
            $this->addFlash('warning', 'Vous devez vous connecter pour liker un événement.');
            $this->redirectToRoute('login');
        }
        return new JsonResponse(['likes' => $post->getLike()]);
    }

    /**
     * Ajoute un like à un post.
     *
     * @param Post $post Le post à liker.
     *
     * @return JsonResponse La réponse JSON contenant le nombre de likes.
     */
    #[Route('/remove_like/{id}', name: 'remove_like')]
    public function deleteLike(Post $post): JsonResponse
    {
        $user = $this->userService->getUser();
        $this->likeService->deletePostLike($post, $user);

        return new JsonResponse(['likes' => $post->getLike()]);
    }

    /**
     * Affiche tous les posts likés par l'utilisateur actuel.
     *
     * @return Response La réponse HTTP contenant la liste des posts likés.
     */
    #[Route('/my_likes', name: 'list_my_likes')]
    public function showMyLikes(): Response
    {
        if ($this->userService->isLogin()) {
            $user = $this->userService->getUser();
        } else {
            $this->addFlash('warning', 'Vous devez vous connecter pour voir vos likes.');
            return $this->redirectToRoute('login');
        }

        $likedPosts = $this->likeService->getPostsLikedByUser($user);

        return $this->render('user/eventPost/likes.html.twig', [
            'likedPosts' => $likedPosts,
        ]);
    }
}
