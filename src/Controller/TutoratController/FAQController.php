<?php

namespace App\Controller\TutoratController;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\FAQService;

#[Route('/faq')]
class FAQController extends AbstractController
{
    private FAQService $faqService;

    public function __construct(FAQService $faqService)
    {
        $this->faqService = $faqService;
    }

    #[Route('/', name: 'faq_index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('faq/index.html.twig', [
            'faqs' => $this->faqService->getAllFAQs(),
        ]);
    }
}

