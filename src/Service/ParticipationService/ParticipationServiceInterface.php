<?php

namespace App\Service\ParticipationService;

use App\Entity\User;

interface ParticipationServiceInterface
{
    public function getParticipationByUser(User $user): array;

    public function findParticipationByUserAndEvent(int $userId, int $eventId);
}