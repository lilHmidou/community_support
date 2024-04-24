<?php

namespace App\Service\RoleService;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

interface RoleRedirectorServiceInterface
{
    public function redirect(): RedirectResponse;
    public function addSuccessMessage(SessionInterface $session): void;
}