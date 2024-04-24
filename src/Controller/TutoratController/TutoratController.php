<?php

namespace App\Controller\TutoratController;

use App\Entity\Etudiant;
use App\Entity\Mentor;
use App\Entity\Program;
use App\Form\EtudiantType;
use App\Form\MentorType;
use App\Form\ProgramType;
use App\Repository\ProgramRepository;
use App\Service\TestimoniesService\TestimoniesServiceImpl;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TutoratController extends AbstractController
{
    private TestimoniesServiceImpl $testimoniesService;
    private Security $security;
    private EntityManagerInterface $entityManager;


    public function __construct(Security $security, EntityManagerInterface $entityManager, TestimoniesServiceImpl $testimoniesService)
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

        return $this->render('tutorat/home_tutorat.html.twig', [
            'testimonies' => $testimonies,
            'programPosts' => $programs
        ]);
    }

    #[Route('/profil/tutorat', name: 'profil_tutorat', methods: ['GET', 'POST'])]
    public function show(FormFactoryInterface $formFactory): Response
    {
        // Récupérer l'utilisateur connecté
        $user = $this->getUser();

        // Déterminer si l'utilisateur est mentor ou étudiant
        $userTutorat = $user->getUserTutorat();

        if (in_array('ROLE_MENTOR', $user->getRoles())) {
            // Si l'utilisateur est mentor, créer le formulaire mentor
            $form = $formFactory->create(MentorType::class, $userTutorat);
        } elseif (in_array('ROLE_ETUDIANT', $user->getRoles())) {
            // Si l'utilisateur est étudiant, créer le formulaire étudiant
            $form = $formFactory->create(EtudiantType::class, $userTutorat);
        } else {
            // Si aucun formulaire n'est créé, afficher un message approprié ou rediriger
            $this->addFlash('warning', 'Aucun profil tutorat ne vous est associé.');
            return $this->redirectToRoute('home'); // Ou autre route pertinente
        }

        return $this->render('tutorat/profil.html.twig', [
            'tutoratForm' => $form->createView(),
            'user' => $user,
        ]);
    }

    #[Route('/profil/tutorat/update', name: 'update_tutorat', methods: ['GET', 'POST'])]
    public function update(FormFactoryInterface $formFactory, Request $request, EntityManagerInterface $entityManager): Response
    {
        // Récupérer l'utilisateur connecté
        $user = $this->getUser();
        $userTutorat = $user->getUserTutorat();


        if (in_array('ROLE_MENTOR', $user->getRoles())) {
            // Si l'utilisateur est mentor, créer le formulaire mentor
            $form = $formFactory->create(MentorType::class, $userTutorat);
        } elseif (in_array('ROLE_ETUDIANT', $user->getRoles())) {
            // Si l'utilisateur est étudiant, créer le formulaire étudiant
            $form = $formFactory->create(EtudiantType::class, $userTutorat);
        }
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Enregistrer les modifications dans la base de données
            $entityManager->flush();

            $this->addFlash('success', 'Vos informations ont bien été enregistrées.');
            return $this->redirectToRoute('profil_tutorat');
        }

        return $this->render('tutorat/update.html.twig', [
            'editForm' => $form->createView(),
        ]);
    }

    #[Route('/profil/tutorat/delete', name: 'delete_tutorat', methods: ['GET'])]
    public function delete(EntityManagerInterface $entityManager): Response
    {
        // Récupérer l'utilisateur connecté
        $user = $this->getUser();
        $userTutorat = $user->getUserTutorat();

        if (in_array('ROLE_MENTOR', $user->getRoles())) {

            // Récupérer le Mentor (si le UserTutorat est un Mentor)
            $mentor = $entityManager->getRepository(Mentor::class)->find($userTutorat->getId());

            // Supprimer tous les programmes associés au Mentor
            foreach ($mentor->getMentorPrograms() as $program) {
                $entityManager->remove($program);
            }

            // Supprimer le Mentor après avoir supprimé ses relations
            $entityManager->remove($mentor);
            // Supprimer le rôle de mentor
            $roles = $user->getRoles();
            if (($key = array_search('ROLE_MENTOR', $roles)) !== false) {
                unset($roles[$key]);
            }
            $user->setRoles(array_values($roles));
        } elseif (in_array('ROLE_ETUDIANT', $user->getRoles())) {
            // Récupérer l'Etudiant (si le UserTutorat est un Etudiant)
            $etudiant = $entityManager->getRepository(Etudiant::class)->find($userTutorat->getId());

            // Supprimer tous les programmes associés à l'Etudiant
            foreach ($etudiant->getEtudiantPrograms() as $program) {
                $entityManager->remove($program);
            }

            // Supprimer l'Etudiant après avoir supprimé ses relations
            $entityManager->remove($etudiant);
            // Supprimer le rôle de mentor
            $roles = $user->getRoles();
            if (($key = array_search('ROLE_ETUDIANT', $roles)) !== false) {
                unset($roles[$key]);
            }
            $user->setRoles(array_values($roles));
        }

        // Supprimer l'utilisateur de la base de données
        $entityManager->remove($userTutorat);
        $entityManager->flush();

        $this->addFlash('success', 'Votre compte tutorat a été supprimé.');

        // Rediriger l'utilisateur vers la page d'accueil
        return $this->redirectToRoute('home');
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

            return $this->render('tutorat/program_posts.html.twig', [
                'programs' => $programs,
            ]);
        }
    */

}

