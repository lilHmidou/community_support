<?php

namespace App\Controller\TutoratController;

use App\Entity\Etudiant;
use App\Entity\Program;
use App\Form\EtudiantType;
use App\security\Role;
use App\Service\FileUploadService\FileUploadServiceImpl;
use App\Service\UserService\UserServiceImpl;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EtudiantController extends AbstractController
{
    private UserServiceImpl $userService;
    private FileUploadServiceImpl $fileUploadService;
    private EntityManagerInterface $entityManager;


    public function __construct(UserServiceImpl $userService, FileUploadServiceImpl $fileUploadService, EntityManagerInterface $entityManager)
    {
        $this->userService = $userService;
        $this->fileUploadService = $fileUploadService;
        $this->entityManager = $entityManager;
    }

    #[Route('/tutorat/register_etudiant', name: 'registerEtudiant')]
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

        return $this->render('tutorat/etudiant/etudiant_form.html.twig', [
            'etudiantForm' => $form->createView(),
        ]);
    }

    #[Route('/program/{id}', name: 'program_join', requirements: ['id' => '\d+'])]
    public function joinProgram(Program $program, Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $userTutorat = $user->getUserTutorat();

        // Vérifie si l'utilisateur a un objet UserTutorat associé et s'il s'agit bien d'un objet Etudiant
        if (!$userTutorat || !$userTutorat instanceof Etudiant) {
            $this->addFlash('warning', 'Vous devez vous inscrire en tant qu\'étudiant.');
            // Redirige l'utilisateur vers la page d'inscription étudiant
            return $this->redirectToRoute('registerEtudiant');
        }

        $etudiantIds = $program->getEtudiants()->map(fn($etudiant) => $etudiant->getId());

        // Vérifie si l'utilisateur est déjà inscrit au programme
        if ($etudiantIds->contains($userTutorat->getId())) {
            $this->addFlash('warning', 'Vous participez déjà à ce programme.');
            return $this->redirectToRoute('tutorat');
        }

        // Ajoute l'utilisateur au programme
        $program->addEtudiant($userTutorat);
        $entityManager->flush();

        $this->addFlash('success', 'Vous êtes maintenant inscrit au programme.');
        return $this->redirectToRoute('tutorat');
    }
    #[Route('/programs/quit/{id}', name: 'quit_program', methods: ['POST'])]
    public function quitProgram(Request $request, EntityManagerInterface $entityManager, int $id): Response
    {
        $program = $entityManager->getRepository(Program::class)->find($id);

        if (!$program) {
            throw $this->createNotFoundException('Programme introuvable');
        }

        $user = $this->getUser();
        $etudiant = $user->getUserTutorat();

        // Retirer l'étudiant du programme
        $program->removeEtudiant($etudiant);
        $entityManager->flush();

        $this->addFlash('success', 'Vous avez quitté le programme.');
        return $this->redirectToRoute('list_program_posts');

    }
}
