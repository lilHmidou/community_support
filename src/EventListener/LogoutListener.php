<?php

namespace App\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Http\Event\LogoutEvent;

class LogoutListener implements EventSubscriberInterface
{
    public function onLogout(LogoutEvent $event) : void
    {
        // Récupérer le service de session depuis le contrôleur de l'événement
        $session = $event->getRequest()->getSession();

        // Ajouter un message flash
        $session->getFlashBag()->add('success', 'Vous avez été déconnecté avec succès.');
    }

    public static function getSubscribedEvents() : array
    {
        return [
            LogoutEvent::class => 'onLogout',
        ];
    }
}
?>