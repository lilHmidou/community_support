<?php

namespace App\Service\UserService\UserProfilService;

use App\Entity\Mentor;
use App\Form\UserForm\ProfilType;
use App\Service\TutoratService\ProgramService\ProgramServiceInterface;
use App\Service\UserService\UserServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class UserProfileServiceImpl implements UserProfileServiceInterface
{
    private FormFactoryInterface $formFactory;
    private UserServiceInterface $userService;
    private EntityManagerInterface $entityManager;
    private ProgramServiceInterface $programService;

    public function __construct(
        FormFactoryInterface    $formFactory,
        UserServiceInterface    $userService,
        EntityManagerInterface  $entityManager,
        ProgramServiceInterface $programService
    )
    {
        $this->formFactory = $formFactory;
        $this->userService = $userService;
        $this->entityManager = $entityManager;
        $this->programService = $programService;
    }

    public function createProfilForm(): FormInterface
    {
        $user = $this->userService->getUser();

        return $this->formFactory->create(ProfilType::class, $user);
    }

    public function updateUserProfile(): void
    {
        $this->entityManager->flush();
    }

    public function deleteUserProfile(TokenStorageInterface $tokenStorage): void
    {
        $user = $this->userService->getUser();
        $mentor = $user->getUserTutorat();

        if ($mentor instanceof Mentor) {
            $this->programService->removeMentorPrograms($mentor);
        }
        $this->entityManager->remove($user);
        $this->entityManager->flush();

        $tokenStorage->setToken(null);
    }
}