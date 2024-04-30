<?php

namespace App\Controller\UserController;

use App\Repository\EventParticipationRepository;
use App\Service\LikeService\LikeServiceInterface;
use App\Service\ParticipationService\ParticipationServiceInterface;
use App\Service\PostService\PostServiceInterface;
use App\Service\RoleService\RoleRedirectorServiceImpl;
use App\Service\TutoratService\ProgramService\ProgramManagementService\ProgramManagementServiceInterface;
use App\Service\UserService\UserServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/user')]
class UserController extends AbstractController
{
    private PostServiceInterface $postService;
    private ProgramManagementServiceInterface $programManagementService;
    private UserServiceInterface $userService;
    private LikeServiceInterface $likeService;
    private ParticipationServiceInterface $participationService;

    public function __construct(
        PostServiceInterface                $postService,
        ProgramManagementServiceInterface   $programManagementService,
        UserServiceInterface                $userService,
        LikeServiceInterface                $likeService,
        ParticipationServiceInterface       $participationService
    )
    {
        $this->postService = $postService;
        $this->programManagementService = $programManagementService;
        $this->userService = $userService;
        $this->likeService = $likeService;
        $this->participationService = $participationService;

    }

    /**
     * Affiche la page d'accueil de l'utilisateur.
     *
     * @param RoleRedirectorServiceImpl $roleRedirectService Service pour gérer les redirections basées sur les rôles
     * @param SessionInterface $session Interface pour gérer les sessions
     *
     * @return Response La réponse HTTP avec la page d'accueil de l'utilisateur
     */
    #[Route('', name: 'user')]
    public function index(RoleRedirectorServiceImpl $roleRedirectService, SessionInterface $session): Response
    {
        $roleRedirectService->addSuccessMessage($session);
        return $this->render('home/index.html.twig');
    }

    /**
     * Affiche les publications de l'utilisateur connecté.
     *
     * @return Response La réponse HTTP avec les publications de l'utilisateur
     */
    #[Route('/my_posts', name: 'list_my_posts')]
    public function showMyPosts(): Response
    {
        $user = $this->getUser();
        $userPosts = $this->postService->findAllPostsByUser($user);

        return $this->render('user/eventPost/event_posts.html.twig', [
            'userPosts' => $userPosts,
        ]);
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

    /**
     * Affiche les programmes de tutorat de l'utilisateur connecté.
     *
     * @return Response La réponse HTTP avec les programmes de tutorat de l'utilisateur
     */
    #[Route('/my_programs', name: 'list_my_programs')]
    public function showMyPrograms() : Response
    {
        $user = $this->getUser();
        $programs = $this->programManagementService->getUserPrograms($user);

        return $this->render('tutorat/program_posts.html.twig', [
            'programs' => $programs,
        ]);
    }

    #[Route('/my_participations', name: 'list_my_participations')]
    public function showMyParticipation() : Response
    {
        $user = $this->getUser();

        // Vérifier si l'utilisateur est connecté
        if (!$user) {
            // Gérer l'erreur, par exemple rediriger vers la page de connexion
            // ...
        }

        // Récupérer les participations de l'utilisateur connecté

        $participations = $this->participationService->getParticipationByUser($user);

        return $this->render('user/eventPost/participation.html.twig', [
            'participations' => $participations,
        ]);
    }
}