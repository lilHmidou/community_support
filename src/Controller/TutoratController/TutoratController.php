<?php

namespace App\Controller\TutoratController;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\FAQService;
use App\Service\TestimoniesService;

class TutoratController extends AbstractController
{
    private TestimoniesService $testimoniesService;

    public function __construct(TestimoniesService $testimoniesService)
    {
        $this->testimoniesService = $testimoniesService;
    }

    #[Route('/tutorat', name: 'tutorat')]
    public function index(): Response
    {
        $testimonies = $this->testimoniesService->getAllTestimonies();


        return $this->render('tutorat/homeTutorat.html.twig', [
            'testimonies' => $testimonies,
        ]);
    }
}

