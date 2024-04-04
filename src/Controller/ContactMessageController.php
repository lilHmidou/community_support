<?php

namespace App\Controller;

use App\Entity\ContactMessage;
use App\Form\ContactMessageFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactMessageController extends AbstractController
{
    #[Route('/contact', name: 'contact')]
    public function contact(Request $request, EntityManagerInterface $entityManager): Response
    {
        // L'utilisateur doit être connecté pour envoyer un message
        if (!$this->getUser()) {
            $this->addFlash('danger', 'Vous devez être connecté pour envoyer un message.');
            return $this->redirectToRoute('login');
        }

        $contactMessage = new ContactMessage();
        $contactMessage->setUser($this->getUser());

        $form = $this->createForm(ContactMessageFormType::class, $contactMessage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($contactMessage);
            $entityManager->flush();

            $this->addFlash('success', 'Votre message a bien été envoyé !');
            return $this->redirectToRoute('home');
        }

        return $this->render('contact/contact.html.twig', [
            'contactForm' => $form->createView(),
        ]);
    }
}
