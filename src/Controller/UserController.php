<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\NewPasswordType;
use App\Form\ProfilType;
use App\Form\SolidarityPostType;
use App\Repository\PostRepository;
use App\Repository\ProgramRepository;
use App\security\UserAuthenticator;
use App\Service\roleService\RoleRedirectorService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

#[Route('/user')]
class UserController extends AbstractController
{
    #[Route('', name: 'user')]
    public function index(RoleRedirectorService $roleRedirectorService, SessionInterface $session): Response
    {
        $roleRedirectorService->addSuccessMessage($session);
        return $this->render('home/index.html.twig');
    }

    #[Route('/profil', name: 'profil', methods: ['GET', 'POST'])]
    public function show(): Response
    {
        // Récupérer l'utilisateur connecté
        $user = $this->getUser();

        // Créer le formulaire de profil avec les données de l'utilisateur
        $form = $this->createForm(ProfilType::class, $user);

        // Afficher le formulaire pré-rempli dans la vue
        return $this->render('user/show.html.twig', [
            'profilForm' => $form->createView(),
        ]);
    }

    #[Route('/profil/update', name: 'update', methods: ['GET', 'POST'])]
    public function update(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Récupérer l'utilisateur connecté
        $user = $this->getUser();

        // Créer le formulaire de profil avec les données de l'utilisateur
        $form = $this->createForm(ProfilType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Enregistrer les modifications dans la base de données
            $entityManager->flush();

            $this->addFlash('success', 'Vos informations ont bien été enregistrées.');
            return $this->redirectToRoute('profil');
        }

        return $this->render('user/update.html.twig', [
            'editForm' => $form->createView(),
        ]);
    }

    #[Route('/profil/delete', name: 'delete', methods: ['GET'])]
    public function delete(EntityManagerInterface $entityManager, TokenStorageInterface $tokenStorage): Response
    {
        // Récupérer l'utilisateur connecté
        $user = $this->getUser();

        // Supprimer l'utilisateur de la base de données
        $entityManager->remove($user);
        $entityManager->flush();

        // Déconnecter l'utilisateur
        $tokenStorage->setToken(null);

        $this->addFlash('success', 'Votre compte a été supprimé.');

        // Rediriger l'utilisateur vers la page d'accueil
        return $this->redirectToRoute('home');
    }


    #[Route('/update_password', name: 'changePassword', methods: ['GET','POST'])]
    public function updateMdp(
        Request $request,
        UserPasswordHasherInterface $userPasswordHasher,
        UserAuthenticatorInterface $userAuthenticator,
        UserAuthenticator $authenticator,
        EntityManagerInterface $entityManager
    ): Response
    {
        // Créer le formulaire de modification de mot de passe
        $form = $this->createForm(NewPasswordType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Récupérer les données du formulaire
            // Comparez les nouveaux mots de passe
            $newPassword = $form->get('newPassword')->getData();
            $confirmNewPassword = $form->get('confirmNewPassword')->getData();

            if ($newPassword !== $confirmNewPassword) {
                // Ajouter un message flash pour avertir que les mots de passe ne correspondent pas
                $this->addFlash('error', 'Les mots de passe ne sont pas identiques ! Veuillez réessayer.');
                // Rediriger vers la route pour modifier le mot de passe
                return $this->redirectToRoute('changePassword');
            }

            // Récupérer l'utilisateur connecté
            $user = $this->getUser();

            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $newPassword
                ));

            $entityManager->persist($user);
            $entityManager->flush();

            return $userAuthenticator->authenticateUser(
                $user,
                $authenticator,
                $request
            );

            // Rediriger l'utilisateur vers une page de confirmation
            $this->addFlash('success', 'Votre mot de passe a été modifié avec succès.');
            return $this->redirectToRoute('home'); // Remplacez 'profile' par la route vers la page de profil de l'utilisateur
        }

        // Afficher le formulaire dans la vue
        return $this->render('user/update_password.html.twig', [
            'passwordForm' => $form->createView(),
        ]);
    }

    #[Route('/list_users_posts', name: 'list_users_posts')]
    public function listUsersPosts(PostRepository $postRepository): Response
    {
        $userId = $this->getUser()->getId();
        $userPosts = $postRepository->findAllPostsByUserId($userId);

        return $this->render('user/user_posts.html.twig', [
            'userPosts' => $userPosts,
        ]);
    }

    #[Route('/delete_post/{postId}', name: 'delete_post', methods: ['DELETE', 'POST'])]
    public function deletePost(Request $request, EntityManagerInterface $entityManager, int $postId): Response
    {
        $post = $entityManager->getRepository(Post::class)->find($postId);

        if (!$post) {
            throw $this->createNotFoundException('Post not found');
        }

        $entityManager->remove($post);
        $entityManager->flush();

        return $this->redirectToRoute('list_users_posts', ['userId' => $post->getUser()->getId()]);
    }

    #[Route('/edit_post/{postId}', name: 'edit_post')]
    public function editPost(Request $request, EntityManagerInterface $entityManager, int $postId): Response
    {
        $post = $entityManager->getRepository(Post::class)->find($postId);

        $form = $this->createForm(SolidarityPostType::class, $post);
        $form->handleRequest($request);

        return $this->render('user/edit_post.html.twig', [
            'form' => $form->createView(),
            'post' => $post,
        ]);
    }

    #[Route('/update_post/{postId}', name: 'update_post', methods: ['POST'])]
    public function updatePost(Request $request, PostRepository $postRepository, int $postId): Response
    {
        // Récupérer les données du formulaire
        $data = [
            'title' => $request->request->get('title'),
            'description' => $request->request->get('description'),
        ];

        // Mettre à jour le post
        $postRepository->updatePost($postId, $data);

        $this->addFlash('success', 'Votre publication a été modifiée.');
        // Rediriger vers une autre page ou afficher un message de succès
        return $this->redirectToRoute('list_users_posts');
    }

    #[Route('/list_program_posts', name: 'list_program_posts')]
    public function listProgramPosts(ProgramRepository $programRepository): Response
    {
        $userId = $this->getUser()->getId();
        $programPosts = $programRepository->findAllProgramByUserId($userId);

        return $this->render('user/program_posts.html.twig', [
            'programPosts' => $programPosts,
        ]);
    }

}