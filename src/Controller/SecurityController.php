<?php

namespace App\Controller;

use App\Entity\User;
use App\security\UserAuthenticator;
use App\Service\SecurityService\LoginService\LoginServiceImpl;
use App\Service\SecurityService\RegistrationService\RegistrationServiceImpl;
use App\Service\SecurityService\SecurityFormServiceImpl;
use App\Service\UserService\UserServiceImpl;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

class SecurityController extends AbstractController
{
    private SecurityFormServiceImpl $securityFormService;
    private UserServiceImpl $userService;
    private RegistrationServiceImpl $registrationService;

    public function __construct(
        SecurityFormServiceImpl $securityFormService,
        UserServiceImpl         $userService,
        RegistrationServiceImpl $registrationService,
    )
    {
        $this->securityFormService = $securityFormService;
        $this->userService = $userService;
        $this->registrationService = $registrationService;
    }

    /**
     * Affiche le formulaire de connexion et traite les erreurs de connexion.
     *
     * @param LoginServiceImpl $loginService Le service de gestion de connexion.
     *
     * @return Response
     */
    #[Route(path: '/login', name: 'login')]
    public function login(LoginServiceImpl $loginService): Response
    {
        $user = new User();
        // Si l'utilisateur est déjà connecté, redirigez-le vers la page d'accueil
        if ($this->userService->isLogin()) {
            $this->addFlash('error', 'Vous êtes déjà connecté.');
            return $this->redirectToRoute('home');
        }

        $form = $this->registrationService->createRegistrationForm($user);
        $viewData = $this->securityFormService->prepareSecurityForm();

        //Obligé de le mettre, car les 2 formulaires sont sur la même page
        $viewData['registrationForm'] = $form;

        $error = $loginService->getLastLoginError();

        if ($error) {
            $this->addFlash('error', 'Votre email ou mot de passe est incorrect. Veuillez réessayer.');
        }

        return $this->render('security/login-register.html.twig', $viewData);
    }

    /**
     * Gère le processus d'inscription des utilisateurs.
     *
     * Affiche le formulaire d'inscription et traite les soumissions.
     * Si le formulaire est validé, enregistre un nouvel utilisateur et authentifie
     * l'utilisateur nouvellement enregistré.
     *
     * @param Request $request L'objet de requête HTTP.
     * @param UserAuthenticatorInterface $userAuthenticator L'interface d'authentification utilisateur.
     * @param UserAuthenticator $authenticator Le service d'authentification.
     *
     * @return Response La réponse HTTP résultante, généralement le rendu du formulaire ou une redirection.
     */
    #[Route('/register', name: 'register', methods: ['GET','POST'])]
    public function register(
        Request                    $request,
        UserAuthenticatorInterface $userAuthenticator,
        UserAuthenticator          $authenticator
    ): Response
    {

        $user = new User();

        if ($this->userService->isLogin()) {
            $this->addFlash('error', 'Vous êtes déjà connecté.');
            return $this->redirectToRoute('home');
        }

        $form = $this->registrationService->createRegistrationForm(
            $user,
            ['validation_groups' => ['Default']]
            // Ignorer les contraintes de validation spéciales
        );
        $viewData = $this->securityFormService->prepareSecurityForm();
        $viewData['registrationForm'] = $form;

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Enregistrer l'utilisateur et gérer les validations
            $result = $this->registrationService->register($user, $form);

            if (isset($result['error'])) {
                $this->addFlash('error', $result['error'][0]);
            }

            elseif (isset($result['success'])) {
                $this->addFlash('success', $result['success']);

            return $userAuthenticator->authenticateUser(
                $user,
                $authenticator,
                $request
            );
            }
        }

        if ($form->isSubmitted() && !$form->isValid()) {
            $formErrors = $form->getErrors(true);
            $this->addFlash('error', $formErrors[0]->getMessage());
        }

        $viewData['isCheckboxChecked'] = true;

        return $this->render('security/login-register.html.twig', $viewData);
    }
}