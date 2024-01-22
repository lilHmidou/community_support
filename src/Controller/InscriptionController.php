<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class InscriptionController extends AbstractController
{

    #[Route('/ind', name: 'app_inscription')]
    public function index(): Response
    {
        return $this->render('inscription/index.html.twig', [
            'controller_name' => 'InscriptionController',
        ]);
    }

    #[Route('/inscription', name: 'community_support_inscription', methods: ['GET', 'POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = new User();

        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($user);
            $entityManager->flush();

            // Ajoutez un message flash pour informer l'utilisateur du succès
            $this->addFlash('success', 'L\'inscription a été réalisé avec succès.');

            // Redirigez l'utilisateur vers une autre page après l'enregistrement réussi
            return $this->redirectToRoute('app_inscription');
        }

        return $this->render('inscription/create.html.twig', [
            'pageName' => 'Inscription',
            'form' => $form,
        ]);
    }


}
