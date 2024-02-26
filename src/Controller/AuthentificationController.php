<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\AuthentificationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class AuthentificationController extends AbstractController
{
    #[Route('/authentification', name: 'authentification', methods: ['GET', 'POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager, SessionInterface $session): Response
    {

        $form = $this->createForm(AuthentificationType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();
            $email = $formData->getEmail(); // Accès à la propriété "Email" du formulaire
            $password = $formData->getPassword(); // Accès à la propriété "Password" du formulaire

            $existingUser = $entityManager->getRepository(User::class)->findOneBy(['Email' => $email]);

            if ($existingUser && password_verify($password, $existingUser->getPassword())) {


                // Authentification réussie
                $session->set('is_logged_in', true);
                $this->addFlash('success', 'Ravi de vous voir, ' . $existingUser->getFirstName() . ' !');
                return $this->redirectToRoute('home');
            } else {
                // Authentification échouée
                // Affichez un message d'erreur à l'utilisateur
                $this->addFlash('error', 'Identifiants invalides. Veuillez réessayer.');
                return $this->redirectToRoute('authentification');
            }
        }

        return $this->render('authentification/create.html.twig', [
            'pageName' => 'Authentification',
            'form' => $form->createView(),
        ]);
    }


    #[Route('/logout', name: 'logout', methods: ['GET'])]
    public function logout(SessionInterface $session): Response
    {
        $session->remove('is_logged_in');
        $this->addFlash('success', 'Vous avez été déconnecté.');
        return $this->redirectToRoute('home');
    }
}