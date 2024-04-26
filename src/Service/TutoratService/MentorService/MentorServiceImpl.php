<?php

namespace App\Service\TutoratService\MentorService;

use App\Entity\Mentor;
use App\Form\MentorType;
use App\security\Role;
use App\Service\FileUploadService\FileUploadServiceInterface;
use App\Service\UserService\UserServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;

class MentorServiceImpl implements MentorServiceInterface
{
    private FormFactoryInterface $formFactory;
    private EntityManagerInterface $entityManager;
    private FileUploadServiceInterface $fileUploadService;
    private UserServiceInterface $userService;


    public function __construct(
        FormFactoryInterface        $formFactory,
        EntityManagerInterface      $entityManager,
        FileUploadServiceInterface  $fileUploadService,
        UserServiceInterface        $userService,
    )
    {
        $this->formFactory = $formFactory;
        $this->entityManager = $entityManager;
        $this->fileUploadService = $fileUploadService;
        $this->userService = $userService;
    }
    public function createMentorForm(Request $request): FormInterface
    {
        $mentor = new Mentor();
        $form = $this->formFactory->create(MentorType::class, $mentor);
        $form->handleRequest($request);
        return $form;
    }

    public function handleMentorFormSubmission(FormInterface $form): array
    {
        $mentor = $form->getData();
        $file = $form->get('Doc')->getData();

        if ($file) {
            try {
                $newFilename = $this->fileUploadService->uploadFile($file);
                $mentor->setDocPath($newFilename);
            } catch (FileException $e) {
                return ['status' => 'error', 'message' => 'Erreur lors du téléchargement du fichier.'];
            }
        }

        $currentUser = $this->userService->getUser();
        $mentor->setUser($currentUser);

        // Ajouter le rôle "ROLE_MENTOR" à l'utilisateur connecté
        $currentUser->addRole(Role::ROLE_MENTOR);
        $this->entityManager->persist($currentUser);
        $this->entityManager->flush();

        $this->entityManager->persist($mentor);
        $this->entityManager->flush();

        return ['status' => 'success', 'message' => 'Inscription réussie. Veuillez vous reconnecter.'];
    }
}