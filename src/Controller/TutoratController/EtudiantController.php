<?php

namespace App\Controller\TutoratController;

use App\Service\TutoratService\EtudiantService\EtudiantServiceInterface;
use App\Service\UserService\UserServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EtudiantController extends AbstractController
{
    private UserServiceInterface $userService;
    private EtudiantServiceInterface $etudiantService;

    public function __construct(
        UserServiceInterface        $userService,
        EtudiantServiceInterface    $etudiantService
    ) {
        $this->userService = $userService;
        $this->etudiantService = $etudiantService;
    }

    #[Route('/tutorat/register_etudiant', name: 'registerEtudiant')]
    public function create(Request $request): Response
    {
        // Vérifiez si l'utilisateur est connecté
        if (!$this->userService->isLogin()) {
            $this->addFlash('danger', 'Vous devez être connecté pour accéder à cette page.');
            return $this->redirectToRoute('login');
        }

        // Créez le formulaire pour l'inscription de l'étudiant
        $form = $this->etudiantService->createEtudiantForm($request);

        // Gérer le formulaire soumis et valider la logique métier via le service
        if ($form->isSubmitted() && $form->isValid()) {
            $result = $this->etudiantService->handleEtudiantFormSubmission($form);

            if ($result['status'] === 'success') {
                $this->addFlash('success', $result['message']);
                return $this->redirectToRoute('login');
            } elseif ($result['status'] === 'error') {
                $this->addFlash('error', $result['message']);
                return $this->redirectToRoute('tutorat');
            }
        }

        return $this->render('tutorat/etudiant/etudiant_form.html.twig', [
            'etudiantForm' => $form->createView(),
        ]);
    }
}