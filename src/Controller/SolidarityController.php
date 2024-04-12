<?php

namespace App\Controller;

use App\Entity\PostLike;
use App\Entity\Post;
use App\Entity\User;
use App\Form\SolidarityPostType;
use App\Service\LikeService;
use App\Service\PostService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\PostRepository;
use Symfony\Runtime\Symfony\Component;

class SolidarityController extends AbstractController
{

    private Security $security;
    private EntityManagerInterface $entityManager;

    private PostService $postService;

    public function __construct(Security $security, EntityManagerInterface $entityManager, PostService $postService)
    {
        $this->security = $security;
        $this->entityManager = $entityManager;
        $this->postService = $postService;

    }

    #[Route('/solidarity', name: 'solidarity')]
    public function index(PostRepository $postRepository, LikeService $likeService): Response
    {
        $events = $postRepository->findAll();

        // Récupérer l'état du like pour chaque événement (post)
        $likedStates = [];
        foreach ($events as $event) {
            $likedStates[$event->getId()] = $likeService->checkLike($event);
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


    #[Route('/solidarity_form', name: 'solidarity_form')]
    public function createEvent(Request $request): Response
    {
        
        $event = new Post();

        $user = $this->security->getUser();

        // Vérifier si l'utilisateur est connecté
        if ($user) {
            // Associer l'utilisateur au post
            $event->setUser($user);
        } else {
            // Gérer le cas où aucun utilisateur n'est connecté, par exemple, rediriger vers la page de connexion
            $this->addFlash('warning', 'Vous devez vous connecter pour poster un événement.');
            return $this->redirectToRoute('login');
        }

        $event->setLike(0);

        $form = $this->createForm(SolidarityPostType::class, $event);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Traitement du formulaire (sauvegarde de l'événement, etc.)
            $this->entityManager->persist($event);
            $this->entityManager->flush();

            // Redirection vers une autre page après la création de l'événement
            return $this->redirectToRoute('solidarity');
        }


        return $this->render('solidarity/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }




}