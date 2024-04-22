<?php

namespace App\Controller\TutoratController;

use App\Entity\Etudiant;
use App\Form\EtudiantType;
use App\security\Role;
use App\Service\FileUploadService;
use App\Service\UserService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EtudiantController extends AbstractController
{
    private UserService $userService;
    private FileUploadService $fileUploadService;
    private EntityManagerInterface $entityManager;


    public function __construct(UserService $userService, FileUploadService $fileUploadService, EntityManagerInterface $entityManager)
    {
        $this->userService = $userService;
        $this->fileUploadService = $fileUploadService;
        $this->entityManager = $entityManager;
    }

    #[Route('/tutorat/etudiant', name: 'registerEtudiant')]
    public function create(Request $request): Response
    {
        if (!$this->userService->isLogin()) {
            $this->addFlash('danger', 'Vous devez être connecté pour accéder à cette page.');
            return $this->redirectToRoute('login');
        }

        // Récupérer l'utilisateur connecté
        $currentUser = $this->userService->getUser();

        // Vérifier si l'utilisateur est déjà inscrit comme étudiant
        $existingEtudiant = $this->entityManager->getRepository(Etudiant::class)->findOneBy(['user' => $currentUser]);

        if ($existingEtudiant) {
            $this->addFlash('error', 'Vous êtes déjà inscrit comme étudiant.');
            return $this->redirectToRoute('tutorat');
        }

        $etudiant = new Etudiant();
        $form = $this->createForm(EtudiantType::class, $etudiant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('doc')->getData();
            if ($file) {
                try {
                    $newFilename = $this->fileUploadService->uploadFile($file);
                    $etudiant->setDocPath($newFilename);
                } catch (FileException $e) {
                    $this->addFlash('danger', 'Une erreur est survenue lors de l\'envoi de votre fichier.');
                    return $this->redirectToRoute('tutorat');
                }
            }
            $etudiant->setUser($this->userService->getUser());

            // Ajouter le rôle "ROLE_ETUDIANT" à l'utilisateur connecté
            $user = $this->getUser();
            $user->addRole(Role::ROLE_ETUDIANT);
            $this->entityManager->persist($user);
            $this->entityManager->flush();

            $this->entityManager->persist($etudiant);
            $this->entityManager->flush();

            $this->addFlash('success', 'Vous venez d\'être admis en tant qu\'étudiant. Veuillez vous reconnecter.');
            return $this->redirectToRoute('login');
        }

        return $this->render('tutorat/etudiantForm.html.twig', [
            'etudiantForm' => $form->createView(),
        ]);
    }
}
