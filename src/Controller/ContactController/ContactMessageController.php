<?php

namespace App\Controller\ContactController;

use App\Entity\ContactMessage;
use App\Form\ContactMessageFormType;
use App\Service\ContactService\ContactServiceInterface;
use App\Service\UserService\UserServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactMessageController extends AbstractController
{
    private UserServiceInterface $userService;
    private ContactServiceInterface $contactService;

    public function __construct(
        UserServiceInterface $userService,
        ContactServiceInterface $contactService
    )
    {
        $this->userService = $userService;
        $this->contactService = $contactService;
    }

    #[Route('/contact', name: 'contact')]
    public function contact(Request $request): Response
    {
        // Utilisation de UserServiceImpl pour vérifier si l'utilisateur est connecté
        if (!$this->userService->isLogin()) {
            $this->addFlash('danger', 'Vous devez être connecté pour envoyer un message.');
            return $this->redirectToRoute('login');
        }

        $contactMessage = new ContactMessage();
        $contactMessage->setUser($this->getUser());

        $form = $this->createForm(ContactMessageFormType::class, $contactMessage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->contactService->saveContactMessage($contactMessage);
            $this->addFlash('success', 'Votre message a bien été envoyé !');
            return $this->redirectToRoute('home');
        }

        return $this->render('contact/contact.html.twig', [
            'contactForm' => $form->createView(),
        ]);
    }
}
