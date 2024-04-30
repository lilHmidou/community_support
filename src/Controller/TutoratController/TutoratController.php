<?php

namespace App\Controller\TutoratController;

use App\Repository\ProgramRepository;
use App\Service\TestimoniesService\TestimoniesServiceInterface;
use App\Service\TutoratService\TutoratServiceInterface;
use App\Service\UserService\UserServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TutoratController extends AbstractController
{
    private TestimoniesServiceInterface $testimoniesService;
    private TutoratServiceInterface $tutoratService;
    private UserServiceInterface $userService;
    private FormFactoryInterface $formFactory;

    public function __construct(
        TestimoniesServiceInterface $testimoniesService,
        TutoratServiceInterface $tutoratService,
        UserServiceInterface $userService,
        FormFactoryInterface $formFactory
    )
    {
        $this->testimoniesService = $testimoniesService;
        $this->tutoratService = $tutoratService;
        $this->userService = $userService;
        $this->formFactory = $formFactory;
    }

    /**
     * Affiche la page d'accueil du tutorat.
     *
     * @param ProgramRepository $programRepository Le dépôt des programmes.
     *
     * @return Response La réponse HTTP.
     */
    #[Route('/tutorat', name: 'tutorat')]
    public function index(ProgramRepository $programRepository): Response
    {
        $testimonies = $this->testimoniesService->getAllTestimonies();
        $query = $programRepository->createQueryBuilder('p')
            ->orderBy('p.createdAtProgram', 'DESC')
            ->getQuery();

        $programs = $query->getResult();
        return $this->render('tutorat/home_tutorat.html.twig', [
            'testimonies' => $testimonies,
            'programPosts' => $programs
        ]);
    }

    /**
     * Affiche le profil de tutorat de l'utilisateur.
     *
     * @return Response La réponse HTTP.
     */
    #[Route('/profil/tutorat', name: 'profil_tutorat', methods: ['GET', 'POST'])]
    public function show(): Response
    {
        $user = $this->getUser();

        if (!$this->userService->isLogin()) {
            $this->addFlash('warning', 'Vous devez être connecté pour accéder à cette page.');
            return $this->redirectToRoute('login');
        }

        try {
            $form = $this->tutoratService->createFormBasedOnRole($this->formFactory, $user);
        } catch (\LogicException $e) {
            $this->addFlash('warning', 'Aucun profil tutorat ne vous est associé.');
            return $this->redirectToRoute('home');
        }

        return $this->render('tutorat/profil.html.twig', [
            'tutoratForm' => $form->createView(),
            'user' => $user,
        ]);
    }

    /**
     * Met à jour le profil de tutorat de l'utilisateur.
     *
     * @param Request $request La requête HTTP.
     * @param EntityManagerInterface $entityManager Le gestionnaire d'entités.
     *
     * @return Response La réponse HTTP.
     */
    #[Route('/profil/tutorat/update', name: 'update_tutorat', methods: ['GET', 'POST'])]
    public function update(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        if (!$this->userService->isLogin()) {
            $this->addFlash('warning', 'Vous devez être connecté pour accéder à cette page.');
            return $this->redirectToRoute('login');
        }

        try {
            $form = $this->tutoratService->createFormBasedOnRole($this->formFactory, $user);
        } catch (\LogicException $e) {
            $this->addFlash('warning', 'Aucun profil tutorat ne vous est associé.');
            return $this->redirectToRoute('home');
        }

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Vos informations ont bien été enregistrées.');
            return $this->redirectToRoute('profil_tutorat');
        }

        return $this->render('tutorat/update.html.twig', [
            'editForm' => $form->createView(),
        ]);
    }

    /**
     * Supprime le profil de tutorat de l'utilisateur.
     *
     * @param EntityManagerInterface $entityManager Le gestionnaire d'entités.
     *
     * @return Response La réponse HTTP.
     */
    #[Route('/profil/tutorat/delete', name: 'delete_tutorat', methods: ['GET'])]
    public function delete(EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        $this->tutoratService->deleteUserAndProgramsBasedOnRole($entityManager, $user);

        $this->addFlash('success', 'Votre compte tutorat a été supprimé. Veuillez vous reconnecter.');

        return $this->redirectToRoute('home');
    }
}