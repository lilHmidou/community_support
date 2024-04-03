<?php

namespace App\Controller\TutoratController;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MentorController extends AbstractController
{
    #[Route('/tutorat/mentor', name: 'registerMentor')]
    public function create(): Response
    {
        return $this->render('tutorat/mentorForm.html.twig', [
            'controller_name' => 'MentorController',
        ]);
    }
}
