<?php

namespace App\Controller\TutoratController;

use App\Entity\Program;
use App\Form\ProgramType;
use App\Repository\ProgramRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\FAQService;
use App\Service\TestimoniesService;

class TutoratController extends AbstractController
{
    private TestimoniesService $testimoniesService;
    private Security $security;
    private EntityManagerInterface $entityManager;


    public function __construct(Security $security, EntityManagerInterface $entityManager, TestimoniesService $testimoniesService)
    {
        $this->security = $security;
        $this->entityManager = $entityManager;

        $this->testimoniesService = $testimoniesService;
    }

    #[Route('/tutorat', name: 'tutorat')]
    public function index(ProgramRepository $programRepository): Response
    {
        $testimonies = $this->testimoniesService->getAllTestimonies();
        $programs = $programRepository->findAll();

        return $this->render('tutorat/homeTutorat.html.twig', [
            'testimonies' => $testimonies,
            'programPosts' => $programs
        ]);
    }

    #[Route('/tutorat_form', name: 'tutorat_form')]
    public function createEvent(Request $request): Response
    {

        $program = new Program();

        $user = $this->security->getUser();

        // Vérifier si l'utilisateur est connecté
        if ($user) {
            $userTutorat = $user->getUserTutorat();
            $program->setMentor($userTutorat);
        } else {
            // Gérer le cas où aucun utilisateur n'est connecté, par exemple, rediriger vers la page de connexion
            $this->addFlash('warning', 'Vous devez vous connecter pour poster un programme de tutorat.');
            return $this->redirectToRoute('login');
        }

        $form = $this->createForm(ProgramType::class, $program);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Traitement du formulaire (sauvegarde de l'événement, etc.)
            $this->entityManager->persist($program);
            $this->entityManager->flush();

            $this->addFlash('success', 'Votre programme de tutorat a été créé avec succès !');

            // Redirection vers une autre page après la création de l'événement
            return $this->redirectToRoute('tutorat');
        }


        return $this->render('tutorat/program_form.html.twig', [
            'form' => $form->createView(),
        ]);
    }
/*
    #[Route('/tutorat/program', name: 'my_programs')]
    public function showMyPrograms()
    {
        $user = $this->getUser();

        if ($user->hasRole('ROLE_MENTOR')) {
            $programs = $user->getUserTutorat()->getMentorPrograms();
        } elseif ($user->hasRole('ROLE_ETUDIANT')) {
            $programs = $user->getUserTutorat()->getEtudiantPrograms();
        } else {
            throw $this->createAccessDeniedException();
        }

        return $this->render('program/program_posts.html.twig', [
            'programs' => $programs,
        ]);
    }
*/

}

