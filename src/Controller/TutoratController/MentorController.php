<?php

namespace App\Controller\TutoratController;

use App\Service\TutoratService\MentorService\MentorServiceInterface;
use App\Service\UserService\UserServiceImpl;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MentorController extends AbstractController
{
    private UserServiceImpl $userService;
    private MentorServiceInterface $mentorService;

    public function __construct(
        UserServiceImpl         $userService,
        MentorServiceInterface  $mentorService
    )
    {
        $this->userService = $userService;
        $this->mentorService = $mentorService;
    }

    #[Route('/tutorat/register_mentor', name: 'registerMentor')]
    public function registerMentor(Request $request): Response
    {
        if (!$this->userService->isLogin()) {
            $this->addFlash('danger', 'Vous devez être connecté pour accéder à cette page.');
            return $this->redirectToRoute('login');
        }

        $form = $this->mentorService->createMentorForm($request);

        if ($form->isSubmitted() && $form->isValid()) {
        $result = $this->mentorService->handleMentorFormSubmission($form);

        if ($result['status'] === 'success') {
            $this->addFlash('success', $result['message']);
            return $this->redirectToRoute('login');
        } elseif ($result['status'] === 'error') {
            $this->addFlash('error', $result['message']);
            return $this->redirectToRoute('tutorat');
        }
    }

        return $this->render('tutorat/mentor/mentor_form.html.twig', [
            'mentorForm' => $form->createView(),
        ]);
    }
}