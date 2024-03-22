<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        $user = $this->getUser();

        //Si il est déjà connecté, on le redirige vers la page d'accueil
        if ($user) {
            return $this->redirectToRoute('home');
        }

        // Ajouter un message flash en fonction de la présence ou non d'une erreur
        if ($error) {
            $this->addFlash('error', 'Votre email ou mot de passe est incorrect. Veuillez réessayer.');
        }

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
        ]);
    }
}