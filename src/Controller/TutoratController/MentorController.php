<?php

namespace App\Controller\TutoratController;

use App\Entity\Mentor;
use App\Form\MentorType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MentorController extends AbstractController
{
    #[Route('/tutorat/mentor', name: 'registerMentor')]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $mentor = new Mentor();
        $form = $this->createForm(MentorType::class, $mentor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($mentor);
            $entityManager->flush();

            $this->addFlash('success', 'Votre inscription a bien été enregistrée.');
            return $this->redirectToRoute('tutorat');
        }

        return $this->render('tutorat/mentorForm.html.twig', [
            'mentorForm' => $form->createView(),
            'controller_name' => 'MentorController',
        ]);
    }
}
