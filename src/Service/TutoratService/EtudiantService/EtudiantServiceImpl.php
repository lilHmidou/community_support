<?php

namespace App\Service\TutoratService\EtudiantService;

use App\Entity\Etudiant;
use App\Form\EtudiantType;
use App\security\Role;
use App\Service\FileUploadService\FileUploadServiceInterface;
use App\Service\UserService\UserServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormInterface;

class EtudiantServiceImpl implements EtudiantServiceInterface
{
    private EntityManagerInterface $entityManager;
    private FileUploadServiceInterface $fileUploadService;
    private UserServiceInterface $userService;
    private FormFactoryInterface $formFactory;

    public function __construct(
        EntityManagerInterface          $entityManager,
        FileUploadServiceInterface      $fileUploadService,
        UserServiceInterface            $userService,
        FormFactoryInterface            $formFactory
    )
    {
        $this->entityManager = $entityManager;
        $this->fileUploadService = $fileUploadService;
        $this->userService = $userService;
        $this->formFactory = $formFactory;
    }

    public function createEtudiantForm(Request $request): Form
    {
        $etudiant = new Etudiant();
        $form = $this->formFactory->create(EtudiantType::class, $etudiant);
        $form->handleRequest($request);
        return $form;
    }

    public function handleEtudiantFormSubmission(Form $form): array
    {
        $etudiant = $form->getData();
        $file = $form->get('Doc')->getData();

        if ($file) {
            try {
                $newFilename = $this->fileUploadService->uploadFile($file);
                $etudiant->setDocPath($newFilename);
            } catch (FileException $e) {
                return ['status' => 'error', 'message' => 'Erreur lors du téléchargement du fichier.'];
            }
        }

        $currentUser = $this->userService->getUser();
        $etudiant->setUser($currentUser);

        // Ajouter le rôle "ROLE_ETUDIANT" à l'utilisateur connecté
        $currentUser->addRole(Role::ROLE_ETUDIANT);
        $this->entityManager->persist($currentUser);
        $this->entityManager->flush();

        $this->entityManager->persist($etudiant);
        $this->entityManager->flush();

        return ['status' => 'success', 'message' => 'Inscription réussie. Veuillez vous reconnecter.'];
    }
}