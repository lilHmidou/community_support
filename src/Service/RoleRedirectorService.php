<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class RoleRedirectorService
{
    private UrlGeneratorInterface $urlGenerator;
    private TokenStorageInterface $tokenStorage;

    public function __construct(UrlGeneratorInterface $urlGenerator, TokenStorageInterface $tokenStorage)
    {
        $this->urlGenerator = $urlGenerator;
        $this->tokenStorage = $tokenStorage;
    }

    public function redirect(): RedirectResponse
    {
        $user = $this->tokenStorage->getToken()->getUser();
        $roles = $user->getRoles();

        if (in_array('ROLE_ADMIN', $roles, true)) {
            return new RedirectResponse($this->urlGenerator->generate('admin'));
        } elseif (in_array('ROLE_USER', $roles, true)) {
            return new RedirectResponse($this->urlGenerator->generate('user'));
        }
    }

    public function addSuccessMessage(SessionInterface $session): void
    {
        $user = $this->tokenStorage->getToken()->getUser();
        if (!$session->has('welcome_message_displayed')) {
            $session->getFlashBag()->add('success', 'Ravi de vous voir, ' . $user->getFirstName() . ' !');
            $session->set('welcome_message_displayed', true);
        }
    }
}


?>