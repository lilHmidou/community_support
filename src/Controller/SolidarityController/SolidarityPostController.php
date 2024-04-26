<?php

namespace App\Controller\SolidarityController;

use App\Entity\Post;
use App\Form\SolidarityPostType;
use App\Repository\PostRepository;
use App\Service\PostService\PostServiceInterface;
use App\Service\UserService\UserServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SolidarityPostController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private UserServiceInterface $userService;
    private PostServiceInterface $postService;

    public function __construct(
        EntityManagerInterface  $entityManager,
        UserServiceInterface    $userService,
        PostServiceInterface    $postService
    )
    {
        $this->entityManager = $entityManager;
        $this->userService = $userService;
        $this->postService = $postService;
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
}