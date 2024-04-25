<?php

namespace App\Controller\AdminController;

use App\Service\RoleService\RoleRedirectorServiceImpl;
use App\Service\RoleService\RoleServiceImpl;
use App\Service\UserService\UserServiceImpl;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class AdminController extends AbstractController
{
    private RoleServiceImpl $roleService;
    private AuthorizationCheckerInterface $authorizationChecker;
    private UserServiceImpl $userService;

    public function __construct(
        RoleServiceImpl $roleService,
        AuthorizationCheckerInterface $authorizationChecker,
        UserServiceImpl $userService
    )
    {
        $this->roleService = $roleService;
        $this->authorizationChecker = $authorizationChecker;
        $this->userService = $userService;
    }

    /**
     * Route pour la page d'administration.
     *
     * @param RoleRedirectorServiceImpl $roleRedirectService Service pour ajouter des messages de succès
     * @param Session $session La session actuelle pour stocker des données de session
     *
     * @return Response La réponse rendue de la page d'administration
     */
    #[Route('/admin', name: 'admin')]
    public function index(RoleRedirectorServiceImpl $roleRedirectService, Session $session): Response
    {
        $roleRedirectService->addSuccessMessage($session);

        // Supprimer le rôle USER de l'utilisateur actuel
        $user = $this->getUser();

        // Vérifier si l'utilisateur a le rôle ROLE_USER et s'il est connecté
        if ($this->userService->isLogin() && $this->authorizationChecker->isGranted('ROLE_USER')) {
            // Supprimer le rôle USER de l'utilisateur actuel
            $this->roleService->removeRole($user, 'ROLE_USER');
        }

        return $this->render('home/index.html.twig');
    }
}