<?php

namespace App\Controller;

use App\Form\ProgramType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use App\Entity\Program;
use App\Entity\Etudiant;
use App\Repository\ProgramRepository;
use Doctrine\ORM\EntityManagerInterface;

class ProgramController extends AbstractController
{
    #[Route('/programs/edit/{id}', name: 'edit_prog')]
    public function editProgram(Request $request, EntityManagerInterface $entityManager, int $id): Response
    {
        $program = $entityManager->getRepository(Program::class)->find($id);

        if (!$program) {
            throw $this->createNotFoundException('Programme introuvable');
        }

        $form = $this->createForm(ProgramType::class, $program);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Le programme a été modifié.');
            return $this->redirectToRoute('list_program_posts');
        }

        return $this->render('user/edit_prog.html.twig', [
            'programForm' => $form->createView(),
            'program' => $program,
        ]);
    }

    #[Route('/programs/delete/{id}', name: 'delete_program', methods: ['DELETE','POST'])]
    public function deleteProgram(EntityManagerInterface $entityManager, int $id): Response
    {
        $program = $entityManager->getRepository(Program::class)->find($id);

        if (!$program) {
            throw $this->createNotFoundException('Programme introuvable');
        }

        $entityManager->remove($program);
        $entityManager->flush();

        $this->addFlash('success', 'Le programme a été supprimé.');

        return $this->redirectToRoute('list_program_posts');
    }

    #[Route('/program/{id}', name: 'program_join', requirements: ['id' => '\d+'])]
    public function joinProgram(Program $program, Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $userTutorat = $user->getUserTutorat();

        // Vérifie si l'utilisateur a un objet UserTutorat associé et s'il s'agit bien d'un objet Etudiant
        if (!$userTutorat || !$userTutorat instanceof Etudiant) {
            $this->addFlash('warning', 'Vous devez vous inscrire en tant qu\'étudiant.');
            // Redirige l'utilisateur vers la page d'inscription étudiant
            return $this->redirectToRoute('registerEtudiant');
        }

        $etudiantIds = $program->getEtudiants()->map(fn($etudiant) => $etudiant->getId());

        // Vérifie si l'utilisateur est déjà inscrit au programme
        if ($etudiantIds->contains($userTutorat->getId())) {
            $this->addFlash('warning', 'Vous participez déjà à ce programme.');
            return $this->redirectToRoute('tutorat');
        }

        // Ajoute l'utilisateur au programme
        $program->addEtudiant($userTutorat);
        $entityManager->flush();

        $this->addFlash('success', 'Vous êtes maintenant inscrit au programme.');
        return $this->redirectToRoute('tutorat');
    }
    #[Route('/programs/quit/{id}', name: 'quit_program', methods: ['POST'])]
    public function quitProgram(Request $request, EntityManagerInterface $entityManager, int $id): Response
    {
        $program = $entityManager->getRepository(Program::class)->find($id);

        if (!$program) {
            throw $this->createNotFoundException('Programme introuvable');
        }

        $user = $this->getUser();
        $etudiant = $user->getUserTutorat();

        // Retirer l'étudiant du programme
        $program->removeEtudiant($etudiant);
        $entityManager->flush();

        $this->addFlash('success', 'Vous avez quitté le programme.');
        return $this->redirectToRoute('list_program_posts');

    }
}