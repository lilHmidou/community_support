<?php

namespace App\Service\RoleService;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class RoleServiceImpl implements RoleServiceInterface
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function updateRole(User $user, array $newRoles): void
    {
        $user->setRoles($newRoles);

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    public function removeRole(User $user, string $role): void
    {
        // Récupérer les rôles actuels de l'utilisateur
        $roles = $user->getRoles();

        // Trouver l'index du rôle à supprimer
        $index = array_search($role, $roles, true);

        // Supprimer le rôle s'il est trouvé
        if ($index !== false) {
            unset($roles[$index]);
        }

        // Mettre à jour les rôles de l'utilisateur
        $user->setRoles($roles);

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
}