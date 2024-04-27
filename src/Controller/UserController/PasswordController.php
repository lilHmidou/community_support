<?php

namespace App\Controller\UserController;

use App\Exception\PasswordMismatchException;
use App\Form\UserForm\NewPasswordType;
use App\Service\SecurityService\PasswordService\PasswordServiceInterface;
use App\Service\SecurityService\ValidateRegistrationService\ValidateRegistrationServiceImpl;
use App\Service\SecurityService\ValidateRegistrationService\ValidateRegistrationServiceInterface;
use App\Service\UserService\UserServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PasswordController extends AbstractController

{
    private PasswordServiceInterface $passwordService;
    private UserServiceInterface $userService;
    private ValidateRegistrationServiceInterface $validateRegistrationService;
    public function __construct(
        PasswordServiceInterface                $passwordService,
        UserServiceInterface                    $userService,
        ValidateRegistrationServiceInterface    $validateRegistrationService
    )
    {
        $this->passwordService = $passwordService;
        $this->userService = $userService;
        $this->validateRegistrationService = $validateRegistrationService;
    }

    /**
     * Met à jour le mot de passe de l'utilisateur.
     *
     * @param Request $request La requête HTTP, utilisée pour gérer le formulaire.
     *
     * @return Response La réponse HTTP, généralement une redirection après mise à jour réussie ou un rendu du formulaire.
     */
    #[Route('/update_password', name: 'updatePassword', methods: ['GET','POST'])]
    public function update(Request $request): Response
    {
        $form = $this->createForm(NewPasswordType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->validateRegistrationService->checkPasswordMatch($form, 'new');
                $passwordData = $this->passwordService->getPasswordData($form, 'new');

                $this->passwordService->updatePassword($this->userService->getUser(), $form, $passwordData, 'new');

                $this->addFlash('success', 'Votre mot de passe a été modifié avec succès.');
                return $this->redirectToRoute('home');
            } catch (PasswordMismatchException $e) {
                $this->addFlash('error', $e->getMessage());
                return $this->redirectToRoute('updatePassword');
            } catch (\Exception $e) {
                $this->addFlash('error', 'Une erreur inattendue s\'est produite: ' . $e->getMessage());
                return $this->redirectToRoute('updatePassword');
            }
        } elseif ($form->isSubmitted() && !$form->isValid()) {
            $formErrors = $this->validateRegistrationService->validateFormErrors($form);
            $this->addFlash('error', reset($formErrors));
        }

        return $this->render('user/profil/update_password.html.twig', [
            'passwordForm' => $form->createView(),
        ]);
    }
}