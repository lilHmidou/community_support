<?php

namespace App\Controller;

use App\Repository\FAQRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/faq')]
class FAQController extends AbstractController
{
    #[Route('/', name: 'faq_index', methods: ['GET'])]
    public function index(FAQRepository $faqRepository): Response
    {
        return $this->render('faq/index.html.twig', [
            'faqs' => $faqRepository->findAll(),
        ]);
    }

}
