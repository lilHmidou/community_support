<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\User;
use App\Form\SolidarityPostType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\PostRepository;

class SolidarityController extends AbstractController
{

    #[Route('/solidarity', name: 'solidarity')]
    public function index(PostRepository $postRepository): Response
    {
        $events = $postRepository->findAll();
        return $this->render('solidarity/index.html.twig', [
            'events' => $events,
        ]);
    }

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/solidarity_form', name: 'solidarity_form')]
    public function createEvent(Request $request): Response
    {

        $event = new Post();
        $event->setUserId(3);
        $form = $this->createForm(SolidarityPostType::class, $event);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Traitement du formulaire (sauvegarde de l'événement, etc.)
            $this->entityManager->persist($event);
            $this->entityManager->flush();

            // Redirection vers une autre page après la création de l'événement
            return $this->redirectToRoute('solidarity/index.html.twig');
        }

        return $this->render('solidarity/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }


}