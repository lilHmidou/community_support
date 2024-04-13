<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin')]
class AdminController extends AbstractController
{
    #[Route('', name: 'admin')]
    public function index(): Response
    {
        // Logique pour afficher le tableau de bord de l'administrateur
        return $this->render('home/index.html.twig');
    }

    #[Route('/handler', name: 'handler')]
    public function handler(UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();

        return $this->render('admin/handler.html.twig', [
            'users' => $users,
        ]);
    }

}