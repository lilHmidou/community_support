<?php

namespace App\Controller\TutoratController;

use App\Repository\TestimoniesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TutoratController extends AbstractController
{
    #[Route('/tutorat', name: 'tutorat')]
    public function index(TestimoniesRepository $testimoniesRepository): Response
    {
        $testimonies = $testimoniesRepository->findAll(); // Récupère tous les témoignages
        // Supposons que tu veux vouloir ajouter des FAQs plus tard
        // $faqs = $faqRepository->findAll();
        return $this->render('tutorat/homeTutorat.html.twig', [
            'testimonies' => $testimonies,
            // 'faqs' => $faqs, // Pour quand tu ajouteras les FAQs
        ]);
    }


}
