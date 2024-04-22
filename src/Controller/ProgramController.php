<?php

namespace App\Controller;

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
}