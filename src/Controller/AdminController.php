<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Service\RoleService\RoleRedirectorServiceImpl;
use App\Service\RoleService\RoleServiceImpl;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class AdminController extends AbstractController
{
    private $roleService;
    private $authorizationChecker;

    public function __construct(RoleServiceImpl $roleService, AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->roleService = $roleService;
        $this->authorizationChecker = $authorizationChecker;
    }

    #[Route('/admin', name: 'admin')]
    public function index(RoleRedirectorServiceImpl $roleRedirectorService, Session $session): Response
    {
        $roleRedirectorService->addSuccessMessage($session);

        // Supprimer le rôle USER de l'utilisateur actuel
        $user = $this->getUser();

        // Vérifier si l'utilisateur a le rôle ROLE_USER
        if ($this->authorizationChecker->isGranted('ROLE_USER')) {
            // Supprimer le rôle USER de l'utilisateur actuel
            $this->roleService->removeRole($user, 'ROLE_USER');
        }

        return $this->render('home/index.html.twig');
    }
}
?>