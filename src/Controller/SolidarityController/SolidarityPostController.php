<?php

namespace App\Controller\SolidarityController;

use App\Entity\EventParticipation;
use App\Entity\Post;
use App\Form\SolidarityPostType;
use App\Repository\PostRepository;
use App\Service\ParticipationService\ParticipationServiceInterface;
use App\Service\PostService\PostServiceInterface;
use App\Service\UserService\UserServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SolidarityPostController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private UserServiceInterface $userService;
    private PostServiceInterface $postService;
    private ParticipationServiceInterface $participationService;

    public function __construct(
        EntityManagerInterface  $entityManager,
        UserServiceInterface    $userService,
        PostServiceInterface    $postService,
        ParticipationServiceInterface $participationService
    )
    {
        $this->entityManager = $entityManager;
        $this->userService = $userService;
        $this->postService = $postService;
        $this->participationService = $participationService;
    }

    /**
     * Crée un nouveau post d'événement.
     *
     * @param Request $request L'objet de requête HTTP.
     *
     * @return Response La réponse HTTP, soit le formulaire de création, soit une redirection.
     */
    #[Route('/create_post', name: 'solidarity_form')]
    public function create(Request $request): Response
    {
        $event = new Post();

        $form = $this->createForm(SolidarityPostType::class, $event);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->postService->createPost($event);

            $this->addFlash('success', 'Votre événement a été créé avec succès.');
            return $this->redirectToRoute('solidarity');
        }

        return $this->render('user/eventPost/create_post.html.twig', [
            'solidarityForm' => $form->createView(),
        ]);
    }

    /**
     * Met à jour un post d'événement.
     *
     * @param Request $request La requête HTTP.
     * @param int $postId L'ID du post à mettre à jour.
     *
     * @return Response La réponse HTTP, généralement une redirection après succès ou le rendu du formulaire.
     */
    #[Route('/update_post/{postId}', name: 'update_post')]
    public function update(Request $request, int $postId): Response
    {
        $post = $this->postService->getPostById($postId);

        $form = $this->createForm(SolidarityPostType::class, $post);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $this->postService->updatePost($post);

            $this->addFlash('success', 'Votre publication a été modifiée.');
            // Rediriger vers une autre page ou afficher un message de succès
            return $this->redirectToRoute('list_my_posts');
        }

        return $this->render('user/eventPost/update_post.html.twig', [
            'solidarityForm' => $form->createView(),
            'post' => $post,
        ]);
    }

    /**
     * Supprime un post d'événement.
     *
     * @param int $postId L'ID du post à supprimer.
     *
     * @return Response La réponse HTTP, généralement une redirection après suppression réussie.
     */
    #[Route('/delete_post/{postId}', name: 'delete_post', methods: ['DELETE', 'POST'])]
    public function delete(int $postId): Response
    {
        $post = $this->postService->getPostById($postId);

        if (!$post) {
            throw $this->createNotFoundException('Post not found');
        }

        $this->postService->deletePost($post);
        $this->addFlash('success', 'Votre publication a été supprimée.');

        return $this->redirectToRoute('list_my_posts', ['userId' => $post->getUser()->getId()]);
    }

    #[Route('/participate/{eventId}', name: 'participate')]
    public function participate(Request $request, int $eventId): Response
    {

        $userId = $this->getUser()->getId();

        // Vérifier si l'utilisateur et l'événement existent
        if (!$userId) {
            $this->addFlash('error', 'Une erreur s\'est produite lors de la participation à l\'événement. Veuillez réessayer.');
            return $this->render('solidarity/validation.html.twig');
        }

        $existingParticipation = $this->participationService->findParticipationByUserAndEvent($userId, $eventId);


        if ($existingParticipation) {
            $this->addFlash('error', 'Vous participez déjà à cet événement.');
            return $this->render('solidarity/validation.html.twig');
        }

        // Créer une nouvelle instance d'EventParticipation et l'associer à l'utilisateur et à l'événement
        $eventParticipation = new EventParticipation();
        $eventParticipation->setUserId($userId);
        $eventParticipation->setPostId($eventId);

        // Enregistrer l'entité dans la base de données
        $this->entityManager->persist($eventParticipation);
        $this->entityManager->flush();

        // Retourner une réponse avec un message de succès
        $this->addFlash('success', 'Vous avez participé à l\'événement avec succès.');
        return $this->render('solidarity/validation.html.twig');
    }
}