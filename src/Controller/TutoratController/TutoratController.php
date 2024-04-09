<?php

namespace App\Controller\TutoratController;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\FAQService;
use App\Service\TestimoniesService;

class TutoratController extends AbstractController
{
    private FAQService $faqService;
    private TestimoniesService $testimoniesService;

    public function __construct(FAQService $faqService, TestimoniesService $testimoniesService)
    {
        $this->faqService = $faqService;
        $this->testimoniesService = $testimoniesService;
    }

    #[Route('/tutorat', name: 'tutorat')]
    public function index(): Response
    {
        $testimonies = $this->testimoniesService->getAllTestimonies();
        $faqs = $this->faqService->getAllFAQs();
        return $this->render('tutorat/homeTutorat.html.twig', [
            'testimonies' => $testimonies,
            'faqs' => $faqs,
        ]);
    }
}

