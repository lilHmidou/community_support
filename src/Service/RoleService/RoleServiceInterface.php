<?php

namespace App\Service\RoleService;

use App\Entity\User;

interface RoleServiceInterface
{
    public function updateRole(User $user, array $newRoles): void;
    public function removeRole(User $user, string $role): void;
}