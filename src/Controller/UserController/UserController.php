<?php

namespace App\Controller\UserController;

use App\Service\PostService\PostServiceInterface;
use App\Service\RoleService\RoleRedirectorServiceImpl;
use App\Service\TutoratService\ProgramService\ProgramManagementServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/user')]
class UserController extends AbstractController
{
    private PostServiceInterface $postService;
    private ProgramManagementServiceInterface $programManagementService;

    public function __construct(
        PostServiceInterface    $postService,
        ProgramManagementServiceInterface $programManagementService
    )
    {
        $this->postService = $postService;
        $this->programManagementService = $programManagementService;
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
     * Affiche les programmes de tutorat de l'utilisateur connecté.
     *
     * @return Response La réponse HTTP avec les programmes de tutorat de l'utilisateur
     */
    #[Route('/my_program', name: 'list_program_posts')]
    public function showMyPrograms() : Response
    {
        $user = $this->getUser();
        $programs = $this->programManagementService->getUserPrograms($user);

        return $this->render('tutorat/program_posts.html.twig', [
            'programs' => $programs,
        ]);
    }
}