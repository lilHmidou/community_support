<?php

namespace App\Controller\TutoratController;

use App\Entity\Testimonies;
use App\Form\TestimoniesType;
use App\Service\TestimoniesService\TestimoniesServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestimoniesController extends AbstractController
{
    private TestimoniesServiceInterface $testimoniesService;

    public function __construct(TestimoniesServiceInterface $testimoniesService)
    {
        $this->testimoniesService = $testimoniesService;
    }

    /**
     * Crée un nouveau témoignage.
     *
     * @param Request $request La requête HTTP.
     * @return Response La réponse HTTP.
     */
    #[Route('/testimony/new', name: 'testimony_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $testimony = new Testimonies();
        $form = $this->createForm(TestimoniesType::class, $testimony);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->testimoniesService->addTestimony($testimony);
            $this->addFlash('success', 'Votre témoignage a été ajouté avec succès.');
            return $this->redirectToRoute('tutorat');
        }

        return $this->render('testimony/new.html.twig', [
            'testimonyForm' => $form->createView(),
        ]);
    }
}

