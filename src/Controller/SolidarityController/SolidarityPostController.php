<?php

namespace App\Controller\SolidarityController;

use App\Entity\Post;
use App\Form\SolidarityPostType;
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

    public function __construct(
        EntityManagerInterface $entityManager,
        UserServiceInterface $userService
    )
    {
        $this->entityManager = $entityManager;
        $this->userService = $userService;
    }

    /**
     * Crée un nouveau post d'événement.
     *
     * @param Request $request L'objet de requête HTTP.
     *
     * @return Response La réponse HTTP, soit le formulaire de création, soit une redirection.
     */
    #[Route('/create_post', name: 'solidarity_form')]
    public function createPost(Request $request): Response
    {

        $event = new Post();

        // Vérifier si l'utilisateur est connecté
        if ($this->userService->isLogin()) {
            // Associer l'utilisateur au post
            $event->setUser($this->userService->getUser());
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