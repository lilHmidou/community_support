<?php

namespace App\Service\ParticipationService;

use App\Entity\User;
use App\Repository\EventParticipationRepository;

class ParticipationServiceImpl implements ParticipationServiceInterface
{

    private EventParticipationRepository $eventParticipationRepository;

    public function __construct(
        EventParticipationRepository $eventParticipationRepository
    )
    {
        $this->eventParticipationRepository = $eventParticipationRepository;
    }

    public function getParticipationByUser(User $user): array
    {
        return $this->eventParticipationRepository->findAllByUserId($user->getId());
    }

    public function findParticipationByUserAndEvent(int $userId, int $eventId)
    {
        return $this->eventParticipationRepository->findOneBy(['user_id' => $userId, 'post_id' => $eventId]);
    }
}