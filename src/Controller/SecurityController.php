<?php

namespace App\Controller;

use App\Entity\User;
use App\security\UserAuthenticator;
use App\Service\userService\UserFormService;
use App\Service\userService\UserMdpService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

class SecurityController extends AbstractController
{
    private $userFormGenerator;
    private $userMdpGenerator;

    public function __construct(UserFormService $userFormGenerator, UserMdpService $userMdpGenerator)
    {
        $this->userFormGenerator = $userFormGenerator;
        $this->userMdpGenerator = $userMdpGenerator;
    }

    #[Route(path: '/login', name: 'login')]
    public function login(Request $request, RouterInterface $router): Response
    {
        $user = new User();
        // Si l'utilisateur est déjà connecté, redirigez-le vers la page d'accueil
        if ($this->getUser()) {
            return $this->redirectToRoute('home');
        }


        $form = $this->userFormGenerator->createRegistrationForm($user);
        $viewData = $this->userFormGenerator->prepareUserForm();
        $viewData['registrationForm'] = $form;

        $error = $this->userFormGenerator->getErrors();
        // Ajoutez un message flash en cas d'erreur
        if ($error) {
            $this->addFlash('error', 'Votre email ou mot de passe est incorrect. Veuillez réessayer.');
        }

        return $this->render('security/login-register.html.twig', $viewData);

    }

    #[Route('/register', name: 'register')]
    public function register(
        Request                    $request,
        RouterInterface $router,
        UserAuthenticatorInterface $userAuthenticator,
        UserAuthenticator          $authenticator,
        EntityManagerInterface     $entityManager,
    ): Response
    {

        $user = new User();
        // Si l'utilisateur est déjà connecté, redirigez-le vers la page d'accueil
        if ($this->getUser()) {
            return $this->redirectToRoute('home');
        }

        $form = $this->userFormGenerator->createRegistrationForm($user);
        $viewData = $this->userFormGenerator->prepareUserForm();
        $viewData['registrationForm'] = $form;


        // Traitez le formulaire d'inscription ici
        $form = $this->userFormGenerator->createRegistrationForm($user, [
            'validation_groups' => ['Default'], // Ignorer les contraintes de validation spéciales
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Vérifiez si l'adresse e-mail existe déjà
            $existingUser = $entityManager->getRepository(User::class)->findOneBy(['email' => $user->getEmail()]);
            if ($existingUser) {
                $this->addFlash('error', 'L\'adresse e-mail existe déjà.');
                return $this->redirectToRoute('register');
            }

            // Récupération des données de mot de passe via le Generator
            $passwordData = $this->userMdpGenerator->getPasswordData($form, $request);

            // Utilisation de checkPasswordMatch pour vérifier la correspondance des mots de passe
            if (!$this->userMdpGenerator->checkPasswordMatch($passwordData)) {
                $this->addFlash('error', 'Les mots de passe ne sont pas identiques ! Veuillez réessayer.');
                return $this->redirectToRoute('register');
            }

            // Hachage du mot de passe
            $hashedPassword = $this->userMdpGenerator->hashPassword($user, $passwordData['plainPassword']);
            $user->setPassword($hashedPassword);

            $entityManager->persist($user);
            $entityManager->flush();

            return $userAuthenticator->authenticateUser(
                $user,
                $authenticator,
                $request
            );
        } elseif ($form->isSubmitted()) {
            // Récupérer toutes les erreurs de validation du formulaire
            foreach ($form->getErrors(true) as $error) {
                $errors[] = $error->getMessage();
            }
        }


        // Si des erreurs ont été trouvées, n'ajouter que la première erreur au flash error
        if (!empty($errors)) {
            $this->addFlash('error', $errors[0]);
        }

        // Préparation des données pour le rendu de la vue
        $viewData['isCheckboxChecked'] = true;

        // Redirigez l'utilisateur vers une page appropriée après la connexion ou l'inscription
        return $this->render('security/login-register.html.twig', $viewData);
    }
}