<?php

namespace App\Controller\SolidarityController;

use App\Repository\PostRepository;
use App\Service\LikeService\LikeServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Runtime\Symfony\Component;

class SolidarityViewController  extends AbstractController
{
    private LikeServiceInterface $likeService;

    public function __construct(LikeServiceInterface $likeService)
    {
        $this->likeService = $likeService;
    }

    #[Route(name: 'solidarity')]
    public function index(PostRepository $postRepository): Response
    {
        $events = $postRepository->findAll();

        // Récupérer l'état du like pour chaque événement (post)
        $likedStates = [];

        // Stockez les e-mails des utilisateurs dans un tableau
        $emails = [];
        foreach ($events as $event) {
            $likedStates[$event->getId()] = $this->likeService->checkLike($event);
            $user = $event->getUser();

            // Obtenez l'e-mail de cet utilisateur
            $email = $user->getEmail();

            // Stockez l'e-mail dans un tableau avec l'ID du post comme clé
            $postId = $event->getId();
            $emails[$postId] = $email;
        }

        return $this->render('solidarity/index.html.twig', [
            'events' => $events,
            'likedStates' => $likedStates,
            'emails' => $emails,
        ]);
    }
}