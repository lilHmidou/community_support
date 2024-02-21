<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\InscriptionType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class InscriptionController extends AbstractController
{
    #[Route('/inscription', name: 'inscription', methods: ['GET', 'POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = new User();

        $form = $this->createForm(InscriptionType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Hachez le mot de passe avant de l'enregistrer
            $hashedPassword = password_hash($user->getPassword(), PASSWORD_DEFAULT);
            $user->setPassword($hashedPassword);

            $existingUser = $entityManager->getRepository(User::class)->findOneBy(['Email' => $user->getEmail()]);
            if ($existingUser) {
                // Ajoutez un message flash pour informer l'utilisateur que cet e-mail existe déjà
                $this->addFlash('error', 'L\'adresse e-mail existe déjà.');

                // Redirigez l'utilisateur vers la page d'inscription
                return $this->redirectToRoute('inscription');
            }

            $entityManager->persist($user);
            $entityManager->flush();

            // Ajoutez un message flash pour informer l'utilisateur du succès
            $this->addFlash('success', 'L\'inscription a été réalisé avec succès.');

            // Redirigez l'utilisateur vers une autre page après l'enregistrement réussi
            return $this->redirectToRoute('home');
        }

        return $this->render('inscription/create.html.twig', [
            'pageName' => 'Inscription',
            'form' => $form,
        ]);
    }
}