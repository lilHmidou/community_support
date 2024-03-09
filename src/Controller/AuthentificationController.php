<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\AuthentificationType;
use App\Form\RegistrationType;
use Symfony\Component\Form\FormView;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class AuthentificationController extends AbstractController
{
    #[Route('/authentification', name: 'authentification', methods: ['GET', 'POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager, SessionInterface $session): Response
    {
        /* Vérifier si l'utilisateur est déjà connecté */
        if ($session->get('is_logged_in')) {
            // Rediriger vers la page d'accueil ou toute autre page appropriée
            return $this->redirectToRoute('home');
        }

        $form = $this->createForm(AuthentificationType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();

            // Récupérer l'e-mail et le mot de passe soumis dans le formulaire
            $email = $formData->getEmail();
            $password = $formData->getPassword();

            // Récupérer l'utilisateur existant correspondant à l'e-mail fourni
            $existingUser = $entityManager->getRepository(User::class)->findOneBy(['Email' => $email]);

            // Vérifier si un utilisateur avec cet e-mail existe et si le mot de passe est correct
            if ($existingUser && password_verify($password, $existingUser->getPassword())) {
                // Authentification réussie
                $session->set('is_logged_in', true);
                $session->set('user_id', $existingUser->getId());

                // Créer le cookie avec l'identifiant de l'utilisateur
                $cookie = Cookie::create('user_id', $existingUser->getId())->withExpires(new \DateTime('+30 days'));

                // Ajouter le cookie à la réponse
                $response = new Response();
                $response->headers->setCookie($cookie);

                $this->addFlash('success', 'Ravi de vous voir, ' . $existingUser->getFirstName() . ' !');

                return $this->redirectToRoute('home');
            } else {
                // Authentification échouée
                $this->addFlash('error', 'Identifiants invalides. Veuillez réessayer.');
                return $this->redirectToRoute('authentification');
            }
        }

        return $this->render('authentification/create.html.twig', [
            'pageName' => 'Authentification',
            'form' => $form->createView(),
        ]);
    }

    #[Route('/authentification/profil/update', name: 'update', methods: ['GET', 'POST'])]
    public function update(Request $request, EntityManagerInterface $entityManager, SessionInterface $session): Response
    {
        // Récupérer l'utilisateur connecté depuis la session
        $userId = $session->get('user_id');
        $user = $entityManager->getRepository(User::class)->find($userId);

        // Récupérer le mdp pr la vue
        $initialPassword = $session->get('initial_password');

        // Vérifier si l'utilisateur existe
        if (!$user) {
            $this->addFlash('error', 'Utilisateur non trouvé.');
            return $this->redirectToRoute('profil');
        }

        // Créer le formulaire d'édition du profil
        $form = $this->createForm(RegistrationType::class, $user, [
            'action' => $this->generateUrl('update'),
            'is_registration' => false,
        ]);

        // Gérer la soumission du formulaire
        $form->handleRequest($request);

        // Vérifier si le formulaire a été soumis et est valide
        if ($form->isSubmitted() && $form->isValid()) {
            // Mettre à jour les informations de l'utilisateur
            $entityManager->flush();

            $this->addFlash('success', 'Vos informations ont bien été enregistrées.');

            return $this->redirectToRoute('profil');
        }

        // Afficher le formulaire de mise à jour dans la vue Twig
        return $this->render('authentification/show.html.twig', [
            'form' => $form->createView(),
            'pageName' => 'Modification du profil',
            'initialPassword' => $initialPassword,
        ]);
    }

    #[Route('/authentification/profil/delete', name: 'delete', methods: ['GET'])]
    public function delete(Request $request, EntityManagerInterface $entityManager, SessionInterface $session): Response
    {
        // Récupérer l'utilisateur connecté depuis la session
        $userId = $session->get('user_id');
        $user = $entityManager->getRepository(User::class)->find($userId);

        // Vérifier si l'utilisateur existe
        if (!$user) {
            $this->addFlash('error', 'Utilisateur non trouvé.');
            return $this->redirectToRoute('profil');
        }

        // Supprimer l'utilisateur
        $entityManager->remove($user);
        $entityManager->flush();

        $session->remove('is_logged_in');
        // Créer un cookie expiré pour supprimer le cookie 'user_id'
        $cookie = Cookie::create('user_id')->withExpires(new \DateTime('-1 day'));

        // Créer une réponse et ajouter le cookie
        $response = new Response();
        $response->headers->setCookie($cookie);

        // Ajouter un message flash pour informer l'utilisateur de la suppression de son compte
        $this->addFlash('success', 'Votre compte a été supprimé.');

        // Rediriger l'utilisateur vers la page d'accueil
        return $this->redirectToRoute('home');
    }


    #[Route('/logout', name: 'logout', methods: ['GET'])]
    public function logout(SessionInterface $session): Response
    {
        $session->remove('is_logged_in');
        $session->remove('user_id');

        // Créer un cookie expiré pour supprimer le cookie 'user_id'
        $cookie = Cookie::create('user_id')->withExpires(new \DateTime('-1 day'));

        // Créer une réponse et ajouter le cookie
        $response = new Response();
        $response->headers->setCookie($cookie);

        $this->addFlash('success', 'Vous avez été déconnecté.');
        return $this->redirectToRoute('home');
    }

    /*
    #[Route('/authentification/profil', name: 'profil', methods: ['GET', 'POST'])]
    public function show(Request $request, EntityManagerInterface $entityManager, SessionInterface $session): Response {
    */
        /*Avec Kernel ça aurait été mieux, on aurait pas besoin de vérifier pour chq
        action du controller que l'utilisateur est connecté mais je ne me lance pas dedans
        comme on a pas eu de cours dessus
        */

/*$userId = $session->get('user_id');

        // Vérifier si l'utilisateur est connecté
        if (!$userId) {
            // Ajouter un message flash pour informer l'utilisateur qu'il a été déconnecté
            $this->addFlash('error', 'Erreur : Vous avez été déconnecté. Veuillez vous reconnecter.');

            // Rediriger vers la page de connexion si l'utilisateur n'est pas connecté
            return $this->redirectToRoute('home');
        }

        // Charger l'utilisateur correspondant depuis la base de données
        $userRepository = $entityManager->getRepository(User::class);
        $user = $userRepository->find($userId);

        // Récupérer le mdp pr la vue
        $initialPassword = $session->get('initial_password');

        // Passer l'utilisateur à la vue Twig
        return $this->render('authentification/show.html.twig', [
            'user' => $user,
            'initialPassword' => $initialPassword,
            'pageName' => 'Profil',
        ]);
    }*/

    #[Route('/authentification/profil', name: 'profil', methods: ['GET'])]
    public function show(Request $request, EntityManagerInterface $entityManager, SessionInterface $session): Response {
        $userId = $session->get('user_id');

        // Charger l'utilisateur à partir de la base de données en utilisant son ID
        $userRepository = $entityManager->getRepository(User::class);
        $user = $userRepository->find($userId);

        // Vérifier si l'utilisateur est connecté
        if (!$userId || !$user) {
            // Ajouter un message flash pour informer l'utilisateur qu'il a été déconnecté
            $this->addFlash('error', 'Erreur : Vous avez été déconnecté. Veuillez vous reconnecter.');

            // Rediriger vers la page de connexion si l'utilisateur n'est pas connecté
            return $this->redirectToRoute('home');
        }

        // Récupérer le mdp pr la vue
        $initialPassword = $session->get('initial_password');

        // Créer le formulaire d'édition du profil
        $form = $this->createForm(RegistrationType::class, $user, [
            'action' => $this->generateUrl('update'),
            'method' => 'POST',
            'is_registration' => false,
            'attr' => ['class' => 'needs-validation'],
        ]);


        // Passer le formulaire à la vue Twig
        return $this->render('authentification/show.html.twig', [
            'form' => $form->createView(),
            'pageName' => 'Profil',
            'initialPassword' => $initialPassword,
            'user' => $user,
        ]);
    }

}
?>