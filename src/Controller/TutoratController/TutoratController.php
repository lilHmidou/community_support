<?php

namespace App\Controller\TutoratController;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TutoratController extends AbstractController
{
    #[Route('/tutorat', name: 'tutorat')]
    public function index(): Response
    {
        return $this->render('tutorat/homeTutorat.html.twig', [
            'controller_name' => 'TutoratController',
        ]);
    }
}
