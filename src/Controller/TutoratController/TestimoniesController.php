<?php

namespace App\Controller\TutoratController;

use App\Entity\Testimonies;
use App\Form\TestimoniesType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class TestimoniesController extends AbstractController
{ //This controller could be useful if we implement a admin panel to manage testimonies
    #[Route('/testimony/new', name: 'testimony_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $testimony = new Testimonies();
        $form = $this->createForm(TestimoniesType::class, $testimony);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($testimony);
            $entityManager->flush();

            $this->addFlash('success', 'Votre témoignage a été ajouté avec succès.');
            return $this->redirectToRoute('tutorat');
        }

        return $this->render('testimony/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

}
