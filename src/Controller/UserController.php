<?php

namespace App\Controller;

use App\Form\ProfilFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class UserController extends AbstractController
{
    #[Route('/user', name: 'user')]
    public function index(): Response
    {
        $this->addFlash('success', 'Ravi de vous voir, ' . $this->getUser()->getFirstName() . ' !');
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

        // Vérifier si l'utilisateur existe
        if (!$user) {
            $this->addFlash('error', 'Utilisateur non trouvé.');
            return $this->redirectToRoute('profil');
        }

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

        // Vérifier si l'utilisateur existe
        if (!$user) {
            $this->addFlash('error', 'Utilisateur non trouvé.');
            return $this->redirectToRoute('profil');
        }

        // Supprimer l'utilisateur de la base de données
        $entityManager->remove($user);
        $entityManager->flush();

        // Déconnecter l'utilisateur
        $tokenStorage->setToken(null);

        $this->addFlash('success', 'Votre compte a été supprimé.');

        // Rediriger l'utilisateur vers la page d'accueil
        return $this->redirectToRoute('home');

    }
}