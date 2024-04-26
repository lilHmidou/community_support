<?php

namespace App\Controller\UserController;

use App\Entity\Post;
use App\Form\SolidarityPostType;
use App\Form\UserForm\NewPasswordType;
use App\Form\UserForm\ProfilType;
use App\Repository\PostRepository;
use App\security\UserAuthenticator;
use App\Service\RoleService\RoleRedirectorServiceImpl;
use App\Service\UserService\UserServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

class UserController extends AbstractController
{
    private UserServiceInterface $userService;

    public function __construct(UserServiceInterface $userService)
    {
        $this->userService = $userService;
    }
    #[Route('/user', name: 'user')]
    public function index(RoleRedirectorServiceImpl $roleRedirectorService, SessionInterface $session): Response
    {
        $roleRedirectorService->addSuccessMessage($session);
        return $this->render('home/index.html.twig');
    }

    #[Route('/my_posts', name: 'list_my_posts')]
    public function showMyPosts(PostRepository $postRepository): Response
    {
        $userId = $this->getUser()->getId();
        $userPosts = $postRepository->findAllPostsByUserId($userId);

        return $this->render('user/eventPost/event_posts.html.twig', [
            'userPosts' => $userPosts,
        ]);
    }

    #[Route('/my_program', name: 'list_program_posts')]
    public function showMyPrograms() : Response
    {
        $user = $this->getUser();
        $programs=null;

        if ($user->hasRole('ROLE_MENTOR')) {
            $programs = $user->getUserTutorat()->getMentorPrograms();
        } elseif ($user->hasRole('ROLE_ETUDIANT')) {
            $programs = $user->getUserTutorat()->getEtudiantPrograms();
        }

        return $this->render('tutorat/program_posts.html.twig', [
            'programs' => $programs,
        ]);
    }
}