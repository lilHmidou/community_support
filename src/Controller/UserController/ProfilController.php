<?php

namespace App\Controller\UserController;

use App\Service\UserService\UserProfilService\UserProfileServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

#[Route('/user')]
class ProfilController extends AbstractController
{
    private UserProfileServiceInterface $userProfileService;

    public function __construct(UserProfileServiceInterface $userProfileService)
    {
        $this->userProfileService = $userProfileService;
    }

    /**
     * Affiche le profil de l'utilisateur.
     *
     * @return Response La réponse HTTP, généralement le rendu du formulaire.
     */
    #[Route('/profil', name: 'profil', methods: ['GET', 'POST'])]
    public function show(): Response
    {
        $form = $this->userProfileService->createProfilForm();

        return $this->render('user/profil/show.html.twig', [
            'profilForm' => $form->createView(),
        ]);
    }

    /**
     * Met à jour le profil utilisateur.
     *
     * @param Request $request La requête HTTP, utilisée pour gérer le formulaire.
     *
     * @return Response La réponse HTTP, généralement une redirection après mise à jour réussie.
     */
    #[Route('/profil/update', name: 'update', methods: ['GET', 'POST'])]
    public function update(Request $request): Response
    {
        $form = $this->userProfileService->createProfilForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->userProfileService->updateUserProfile($form->getData());

            $this->addFlash('success', 'Vos informations ont bien été enregistrées.');
            return $this->redirectToRoute('profil');
        }

        return $this->render('user/profil/update.html.twig', [
            'editForm' => $form->createView(),
        ]);
    }

    /**
     * Supprime le profil utilisateur.
     *
     * @param TokenStorageInterface $tokenStorage Le service de stockage de jetons.
     *
     * @return Response La réponse HTTP, généralement une redirection après suppression du profil.
     */
    #[Route('/profil/delete', name: 'delete', methods: ['GET'])]
    public function delete(TokenStorageInterface $tokenStorage): Response
    {
        $this->userProfileService->deleteUserProfile($tokenStorage);

        $this->addFlash('success', 'Votre compte a été supprimé.');

        return $this->redirectToRoute('home');
    }
}