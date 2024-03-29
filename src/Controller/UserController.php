<?php

namespace App\Controller;

use App\Form\NewPasswordFormType;
use App\Form\ProfilFormType;
use App\Security\UserAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use MongoDB\Driver\Session;
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
    #[Route('/user', name: 'user')]
    public function index(SessionInterface $session): Response
    {
        // Vérifier si le message de bienvenue a déjà été affiché
        if (!$session->has('welcome_message_displayed')) {
            $this->addFlash('success', 'Ravi de vous voir, ' . $this->getUser()->getFirstName() . ' !');
            // Marquer que le message a été affiché pour ne pas le réafficher
            $session->set('welcome_message_displayed', true);
        }
        return $this->render('home/index.html.twig');
    }

    #[Route('/user/profil', name: 'profil', methods: ['GET', 'POST'])]
    public function show(): Response
    {
        // Récupérer l'utilisateur connecté
        $user = $this->getUser();

        // Créer le formulaire de profil avec les données de l'utilisateur
        $form = $this->createForm(ProfilFormType::class, $user);

        // Afficher le formulaire pré-rempli dans la vue
        return $this->render('user/show.html.twig', [
            'profilForm' => $form->createView(),
        ]);
    }

    #[Route('/user/profil/update', name: 'update', methods: ['GET', 'POST'])]
    public function update(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Récupérer l'utilisateur connecté
        $user = $this->getUser();

        // Créer le formulaire de profil avec les données de l'utilisateur
        $form = $this->createForm(ProfilFormType::class, $user);
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

    #[Route('/user/profil/delete', name: 'delete', methods: ['GET'])]
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


    #[Route('/user/update_password', name: 'changePassword', methods: ['GET','POST'])]
    public function updateMdp(
        Request $request,
        UserPasswordHasherInterface $userPasswordHasher,
        UserAuthenticatorInterface $userAuthenticator,
        UserAuthenticator $authenticator,
        EntityManagerInterface $entityManager
    ): Response
    {
        // Créer le formulaire de modification de mot de passe
        $form = $this->createForm(NewPasswordFormType::class);
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
}