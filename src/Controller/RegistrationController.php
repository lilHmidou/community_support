<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController
{
    #[Route('/registration', name: 'registration', methods: ['GET', 'POST'])]
    public function create(User $user, Request $request, EntityManagerInterface $entityManager, SessionInterface $session): Response
    {
        $form = $this->createForm(RegistrationType::class, $user);

        $form->handleRequest($request);

        /* Vérifier si l'utilisateur est déjà connecté */
        if ($session->get('is_logged_in')) {
            // Rediriger vers la page d'accueil ou toute autre page appropriée
            return $this->redirectToRoute('home');
        }

        if ($form->isSubmitted() && $form->isValid()) {
            // Validation unique de l'email
            $existingUser = $entityManager->getRepository(User::class)->findOneBy(['Email' => $user->getEmail()]);
            if ($existingUser) {
                // Ajoutez un message flash pour informer l'utilisateur que cet e-mail existe déjà
                $this->addFlash('error', 'L\'adresse e-mail existe déjà.');

                // Redirigez l'utilisateur vers la page d'registration
                return $this->redirectToRoute('registration');
            }

            // Validation du mot de passe
            $passwordError = $this->validatePassword($user->getPassword());

            if ($passwordError) {
                // Ajout d'un message d'erreur flash si les conditions de validation du mot de passe ne sont pas remplies
                $this->addFlash('error', $passwordError);

                // Redirection de l'utilisateur vers la page d'registration pour corriger le formulaire
                return $this->redirectToRoute('registration');
            }

            // Vérification de la correspondance entre les mots de passe et leur confirmation
            if ($user->getPassword() !== $form->get('confirmPassword')->getData()) {
                // Ajout d'un message d'erreur flash si les mots de passe ne correspondent pas
                $this->addFlash('error', 'Les mots de passe ne sont pas identiques ! Veuillez réessayer.');

                // Redirection de l'utilisateur vers la page d'registration pour corriger le formulaire
                return $this->redirectToRoute('registration');
            }

            // Hachez le mot de passe avant de l'enregistrer
            $hashedPassword = password_hash($user->getPassword(), PASSWORD_DEFAULT);
            $user->setPassword($hashedPassword);

            $entityManager->persist($user);
            $entityManager->flush();

            // Ajoutez un message flash pour informer l'utilisateur du succès
            $this->addFlash('success', 'L\'inscription a été réalisé avec succès.');

            // Redirigez l'utilisateur vers une autre page après l'enregistrement réussi
            return $this->redirectToRoute('home');
        }


        return $this->render('registration/create.html.twig', [
            'pageName' => 'Inscription',
            'form' => $form,
        ]);
    }
    // Méthode de validation du mot de passe
    private function validatePassword(string $password): ?string
    {
        if (strlen($password) < 8) {
            return 'Le mot de passe doit contenir au moins 8 caractères.';
        }

        if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&.])[A-Za-z\d@$!%*?&.]+$/', $password)) {
            return 'Le mot de passe doit contenir au moins une minuscule, une majuscule, un chiffre et un caractère spécial.';
        }
        return null;
    }
}