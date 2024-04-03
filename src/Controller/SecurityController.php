<?php

namespace App\Controller;

use App\Entity\User;
use App\Generator\UserGenerator\UserFormGenerator;
use App\Generator\UserGenerator\UserMdpGenerator;
use App\Security\UserAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

class SecurityController extends AbstractController
{
    private $userFormGenerator;
    private $userMdpGenerator;

    public function __construct(UserFormGenerator $userFormGenerator, UserMdpGenerator $userMdpGenerator)
    {
        $this->userFormGenerator = $userFormGenerator;
        $this->userMdpGenerator = $userMdpGenerator;

    }

    #[Route(path: '/login', name: 'login')]
    public function login(UserFormGenerator $userFormGenerator): Response
    {
        $user = new User();
        // Si l'utilisateur est déjà connecté, redirigez-le vers la page d'accueil
        if ($this->getUser()) {
            return $this->redirectToRoute('home');
        }

        $error = $this->userFormGenerator->getErrors();

        // Ajoutez un message flash en cas d'erreur
        if ($error) {
            $this->addFlash('error', 'Votre email ou mot de passe est incorrect. Veuillez réessayer.');
        }

        $form = $userFormGenerator->createRegistrationForm($user);
        $viewData = $userFormGenerator->prepareUserForm();
        $viewData['registrationForm'] = $form;


        return $this->render('security/login-signup.html.twig', $viewData);
    }

    #[Route('/register', name: 'signup')]
    public function create(
        Request $request,
        UserAuthenticatorInterface $userAuthenticator,
        UserAuthenticator $authenticator,
        EntityManagerInterface $entityManager,
        UserFormGenerator $userFormGenerator, // Injecté via le paramètre de la méthode
        UserMdpGenerator $userMdpGenerator // Injecté via le paramètre de la méthode
    ): Response
    {

        $user = new User();
        $form = $userFormGenerator->createRegistrationForm($user);
        $form->handleRequest($request);
        $viewData['registrationForm'] = $form;

        //Si il est déjà connecté, on le redirige vers la page d'accueil
        if ($this->getUser()) {
            return $this->redirectToRoute('home');
        }
        if ($form->isSubmitted() && $form->isValid()) {
            // Vérifiez si l'adresse e-mail existe déjà
            $existingUser = $entityManager->getRepository(User::class)->findOneBy(['email' => $user->getEmail()]);
            if ($existingUser) {
                $this->addFlash('error', 'L\'adresse e-mail existe déjà.');
                return $this->redirectToRoute('signup');
            }

            // Récupération des données de mot de passe via le service
            $passwordData = $userMdpGenerator->getPasswordData($form, $request);

            // Utilisation de checkPasswordMatch pour vérifier la correspondance des mots de passe
            if (!$userMdpGenerator->checkPasswordMatch($passwordData)) {
                $this->addFlash('error', 'Les mots de passe ne sont pas identiques ! Veuillez réessayer.');
                return $this->render('security/login-signup.html.twig', $viewData);

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
        $viewData = $userFormGenerator->prepareUserForm();
        $viewData['isCheckboxChecked'] = true;

        return $this->render('security/login-signup.html.twig', $viewData);
    }
}