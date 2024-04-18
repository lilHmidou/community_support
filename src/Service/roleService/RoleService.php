<?php

namespace App\Service\roleService;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class RoleService
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function updateUserRoles(User $user, array $newRoles): void
    {
        // Mettez à jour les rôles de l'utilisateur
        $user->setRoles($newRoles);

        // Persistez les changements dans la base de données
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

        // Persistez les changements dans la base de données
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
}