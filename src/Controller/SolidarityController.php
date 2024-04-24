<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\SolidarityPostType;
use App\Repository\PostRepository;
use App\Service\LikeService\LikeServiceImpl;
use App\Service\PostService\PostServiceImpl;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Runtime\Symfony\Component;

#[Route('/solidarity')]
class SolidarityController extends AbstractController
{

    private Security $security;
    private EntityManagerInterface $entityManager;

    private PostServiceImpl $postService;

    public function __construct(Security $security, EntityManagerInterface $entityManager, PostServiceImpl $postService)
    {
        $this->security = $security;
        $this->entityManager = $entityManager;
        $this->postService = $postService;

    }

    #[Route(name: 'solidarity')]
    public function index(PostRepository $postRepository, LikeServiceImpl $likeService): Response
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


    #[Route('/create_post', name: 'solidarity_form')]
    public function createPost(Request $request): Response
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
            $this->entityManager->persist($event);
            $this->entityManager->flush();

            $this->addFlash('success', 'Votre événement a été créé avec succès.');
            return $this->redirectToRoute('solidarity');
        }


        return $this->render('user/eventPost/create_post.html.twig', [
            'solidarityForm' => $form->createView(),
        ]);
    }
}