<?php

namespace App\Controller\UserController;

use App\Form\UserForm\NewPasswordType;
use App\Service\SecurityService\RegistrationService\PasswordServiceInterface;
use App\Service\UserService\UserServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PasswordController extends AbstractController

{
    private PasswordServiceInterface $passwordService;
    private UserServiceInterface $userService;
    public function __construct(
        PasswordServiceInterface $passwordService,
        UserServiceInterface $userService
    )
    {
        $this->passwordService = $passwordService;
        $this->userService = $userService;
    }

    /**
     * Met à jour le mot de passe de l'utilisateur.
     *
     * @param Request $request La requête HTTP, utilisée pour gérer le formulaire.
     *
     * @return Response La réponse HTTP, généralement une redirection après mise à jour réussie ou un rendu du formulaire.
     */
    #[Route('/update_password', name: 'updatePassword', methods: ['GET','POST'])]
    public function update(Request $request,): Response
    {
        $form = $this->createForm(NewPasswordType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $passwordData = $this->passwordService->getNewPasswordData($form);
                $this->passwordService->updatePassword($this->userService->getUser(), $passwordData);
                $this->addFlash('success', 'Votre mot de passe a été modifié avec succès.');
                return $this->redirectToRoute('home');
            } catch (\RuntimeException $e) {
                $this->addFlash('error', $e->getMessage());
                return $this->redirectToRoute('updatePassword');
            }
        }

        return $this->render('user/profil/update_password.html.twig', [
            'passwordForm' => $form->createView(),
        ]);
    }
}