<?php

namespace App\Controller\TutoratController;

use App\Entity\Mentor;
use App\Form\MentorType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MentorController extends AbstractController
{
    #[Route('/tutorat/mentor', name: 'registerMentor')]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Vérifie si l'utilisateur est connecté
        if (!$this->getUser()) {
            $this->addFlash('danger', 'Vous devez être connecté pour accéder à cette page.');
            return $this->redirectToRoute('login');
        }

        $mentor = new Mentor();
        $form = $this->createForm(MentorType::class, $mentor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('doc')->getData();
            if ($file) {
                $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

                try {
                    $file->move(
                        $this->getParameter('docs_directory'),
                        $newFilename
                    );
                    $mentor->setDocPath($newFilename);
                } catch (FileException $e) {
                    $this->addFlash('danger', 'Une erreur est survenue lors de l\'envoi de votre fichier.');
                    return $this->redirectToRoute('tutorat');
                }
            }

            $user = $this->getUser();
            $mentor->setUser($this->getUser());
            if (!$user) {
                $this->addFlash('danger', 'Un problème est survenu avec l\'utilisateur connecté.');
                return $this->redirectToRoute('login');
            }

            $entityManager->persist($mentor);
            $entityManager->flush();

            $this->addFlash('success', 'Votre inscription a bien été enregistrée.');
            return $this->redirectToRoute('tutorat');
        }

        return $this->render('tutorat/mentorForm.html.twig', [
            'mentorForm' => $form->createView(),
            'controller_name' => 'MentorController',
        ]);
    }
}
