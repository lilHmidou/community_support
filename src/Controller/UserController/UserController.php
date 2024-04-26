<?php

namespace App\Controller\UserController;

use App\Entity\Post;
use App\Form\SolidarityPostType;
use App\Form\UserForm\NewPasswordType;
use App\Form\UserForm\ProfilType;
use App\Repository\PostRepository;
use App\security\UserAuthenticator;
use App\Service\RoleService\RoleRedirectorServiceImpl;
use App\Service\UserService\UserServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

class UserController extends AbstractController
{
    private UserServiceInterface $userService;

    public function __construct(UserServiceInterface $userService)
    {
        $this->userService = $userService;
    }
    #[Route('/user', name: 'user')]
    public function index(RoleRedirectorServiceImpl $roleRedirectorService, SessionInterface $session): Response
    {
        $roleRedirectorService->addSuccessMessage($session);
        return $this->render('home/index.html.twig');
    }

    #[Route('/update_password', name: 'updatePassword', methods: ['GET','POST'])]
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
                return $this->redirectToRoute('updatePassword');
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

            // Rediriger l'utilisateur vers une page de confirmation
            $this->addFlash('success', 'Votre mot de passe a été modifié avec succès.');
            return $this->redirectToRoute('home'); // Remplacez 'profile' par la route vers la page de profil de l'utilisateur
        }

        // Afficher le formulaire dans la vue
        return $this->render('user/profil/update_password.html.twig', [
            'passwordForm' => $form->createView(),
        ]);
    }

    #[Route('/my_posts', name: 'list_my_posts')]
    public function showMyPosts(PostRepository $postRepository): Response
    {
        $userId = $this->getUser()->getId();
        $userPosts = $postRepository->findAllPostsByUserId($userId);

        return $this->render('user/eventPost/event_posts.html.twig', [
            'userPosts' => $userPosts,
        ]);
    }

    #[Route('/update_post/{postId}', name: 'update_post')]
    public function updatePost(Request $request, EntityManagerInterface $entityManager, PostRepository $postRepository, int $postId): Response
    {
        $post = $entityManager->getRepository(Post::class)->find($postId);

        $form = $this->createForm(SolidarityPostType::class, $post);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $post = $form->getData();
            $entityManager->flush();

            $this->addFlash('success', 'Votre publication a été modifiée.');
            // Rediriger vers une autre page ou afficher un message de succès
            return $this->redirectToRoute('list_my_posts');
        }

        return $this->render('user/eventPost/update_post.html.twig', [
            'solidarityForm' => $form->createView(),
            'post' => $post,
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

        return $this->redirectToRoute('list_my_posts', ['userId' => $post->getUser()->getId()]);
    }

    #[Route('/list_program_posts', name: 'list_program_posts')]
    public function showMyPrograms() : Response
    {
        $user = $this->getUser();
        $programs=null;

        if ($user->hasRole('ROLE_MENTOR')) {
            $programs = $user->getUserTutorat()->getMentorPrograms();
        } elseif ($user->hasRole('ROLE_ETUDIANT')) {
            $programs = $user->getUserTutorat()->getEtudiantPrograms();
        }

        return $this->render('tutorat/program_posts.html.twig', [
            'programs' => $programs,
        ]);
    }
}