<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Security\UserAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'register')]
    public function create(
        Request $request,
        UserPasswordHasherInterface $userPasswordHasher,
        UserAuthenticatorInterface $userAuthenticator,
        UserAuthenticator $authenticator,
        EntityManagerInterface $entityManager
    ): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        //Si il est déjà connecté, on le redirige vers la page d'accueil
        if ($this->getUser()) {
            return $this->redirectToRoute('home');
        }

        if ($form->isSubmitted() && $form->isValid()) {
            // Vérifiez si l'adresse e-mail existe déjà
            $existingUser = $entityManager->getRepository(User::class)->findOneBy(['email' => $user->getEmail()]);
            if ($existingUser) {
                $this->addFlash('error', 'L\'adresse e-mail existe déjà.');
                return $this->redirectToRoute('register');
            }

            // Comparez le mot de passe et le champ de confirmation
            $plainPassword = $form->get('plainPassword')->getData();
            $confirmPassword = $request->request->get('confirmPassword');

            if ($plainPassword !== $confirmPassword) {
                // Ajoutez une erreur au formulaire
                $this->addFlash('error', 'Les mots de passe ne sont pas identiques ! Veuillez réessayer.');
                // Retournez la vue du formulaire avec les erreurs
                return $this->render('registration/register.html.twig', [
                    'registrationForm' => $form->createView(),
                ]);
            }

            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $plainPassword                )
            );

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

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
