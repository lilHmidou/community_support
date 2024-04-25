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

    #[Route('/register', name: 'register')]
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

        // Traitez le formulaire d'inscription ici
        $form = $this->registrationService->createRegistrationForm($user, [
            'validation_groups' => ['Default'], // Ignorer les contraintes de validation spéciales
        ]);
        $viewData = $this->securityFormService->prepareSecurityForm();
        $viewData['registrationForm'] = $form;

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $errors = [];
            try {
                if ($form->isValid()) {
                    $result = $this->registrationService->register($user, $form);
                    if (isset($result['error'])) {
                        $errors = $result['error'];
                    }
                    $this->addFlash('success', $result['success']);
                    return $userAuthenticator->authenticateUser(
                            $user,
                            $authenticator,
                            $request
                    );
                } else {
                    foreach ($form->getErrors(true) as $error) {
                        $errors[] = $error->getMessage();
                    }
                }
            } catch (\Exception $e) {
                // Gérer les exceptions inattendues
                $errors[] = 'Une erreur inattendue s\'est produite. Veuillez réessayer plus tard.';
            }

            if (!empty($errors)) {
                // Ajouter seulement la première erreur au flash
                $this->addFlash('error', $errors[0]);

                // Redirigez vers le formulaire en cas d'erreur
                return $this->redirectToRoute('register');
            }
        }

        // Préparation des données pour le rendu de la vue
        $viewData['isCheckboxChecked'] = true;

        // Redirigez l'utilisateur vers une page appropriée après la connexion ou l'inscription
        return $this->render('security/login-register.html.twig', $viewData);
    }
}