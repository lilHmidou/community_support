<?php

namespace App\Controller\TutoratController;

use App\Repository\FAQRepository;
use App\Repository\TestimoniesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class  TutoratController extends AbstractController
{
    #[Route('/tutorat', name: 'tutorat')]
    public function index(TestimoniesRepository $testimoniesRepository, FAQRepository $faqRepository): Response
    {
        $testimonies = $testimoniesRepository->findAll();
        $faqs = $faqRepository->findAll();
        return $this->render('tutorat/homeTutorat.html.twig', [
            'testimonies' => $testimonies,
            'faqs' => $faqs,
        ]);
    }


}
