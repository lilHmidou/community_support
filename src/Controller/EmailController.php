<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\EmailService\EmailServiceImpl;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EmailController extends AbstractController
{
    private EmailServiceImpl $emailService;

    public function __construct(EmailServiceImpl $emailService)
    {
        $this->emailService = $emailService;
    }

    /**
     * Envoie un email à l'organisateur d'un événement.
     *
     * @param Request $request La requête HTTP.
     * @param string $eventId L'ID de l'événement.
     *
     * @return Response La réponse HTTP, généralement une redirection après l'envoi de l'email.
     */
    #[Route('/send-email/{eventId}', name: 'send_email')]
    public function sendEmail(Request $request, string $eventId): Response
    {
        $eventId = intval($eventId);

        $sender = $this->getUser();
        if (!$sender) {
            $this->addFlash('error', 'Vous devez être connecté pour envoyer un message.');
            return $this->redirectToRoute('login');
        }

        $recipient = $this->emailService->findUserByPostId($eventId);
        if (!$recipient || !$recipient->getEmail()) {
            $this->addFlash('error', 'Aucun organisateur trouvé pour cet événement.');
            return $this->redirectToRoute('solidarity');
        }

        $message = $request->request->get('message', 'Pas de message fourni');
        $this->emailService->sendEmail($sender, $recipient, "Message de l'événement", $message);
        $this->addFlash('success', 'Votre message a été envoyé avec succès.');
        return $this->redirectToRoute('solidarity', ['id' => $eventId]);
    }

}