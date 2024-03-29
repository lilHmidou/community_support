<?php

namespace App\Controller;

use App\Entity\PostLike;
use App\Entity\Post;
use App\Entity\User;
use App\Form\SolidarityPostType;
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

    public function __construct(Security $security, EntityManagerInterface $entityManager)
    {
        $this->security = $security;
        $this->entityManager = $entityManager;

    }

    #[Route('/solidarity', name: 'solidarity')]
    public function index(PostRepository $postRepository): Response
    {
        $events = $postRepository->findAll();
        return $this->render('solidarity/index.html.twig', [
            'events' => $events,
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

    #[Route('/add_like/{id}', name: 'add_like')]
    public function addLike(Post $post): JsonResponse
    {
        $user = $this->security->getUser();
        if($user){
            $currentLikes = $post->getLike();
            $post->setLike($currentLikes + 1);
            $like = new PostLike();
            $like->setPostId($post->getId());
            $like->setUserId($user->getId());

            $this->entityManager->persist($like);

            $this->entityManager->flush();
        }else{
            //$this->addFlash('warning', 'Vous devez vous connecter pour liker un événement.');
        }

        return new JsonResponse(['likes' => $post->getLike()]);
    }

    #[Route('/save_like', name: 'save_like')]
    public function saveLike(Post $post): void
    {
        $user = $this->security->getUser();

        $like = new PostLike();
        $like->setPostId($post);
        $like->setUserId($user);

        $this->entityManager->persist($like);
        $this->entityManager->flush();


    }

    #[Route('/remove_like/{id}', name: 'remove_like')]
    public function removeLike(Post $post): JsonResponse
    {
        $currentLikes = $post->getLike();
        $post->setLike($currentLikes - 1);
        $this->entityManager->flush();

        return new JsonResponse(['likes' => $post->getLike()]);
    }

}