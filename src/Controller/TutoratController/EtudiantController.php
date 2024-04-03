<?php

namespace App\Controller\TutoratController;

use App\Entity\Etudiant;
use App\Form\EtudiantType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EtudiantController extends AbstractController
{
    #[Route('/tutorat/etudiant', name: 'registerEtudiant')]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $etudiant = new Etudiant();
        $form = $this->createForm(EtudiantType::class, $etudiant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($etudiant);
            $entityManager->flush();

            $this->addFlash('success', 'Votre inscription a bien été enregistrée.');
            return $this->redirectToRoute('tutorat');
        }

        return $this->render('tutorat/etudiantForm.html.twig', [
            'etudiantForm' => $form->createView(),
            'controller_name' => 'EtudiantController',
        ]);
    }
}
