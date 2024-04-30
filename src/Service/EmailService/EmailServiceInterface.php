<?php

namespace App\Service\EmailService;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Response;

interface EmailServiceInterface
{
    public function sendEmail(User $sender, User $recipient, string $subject, string $htmlContent): void;
    public function sendParticipateEmail(User $participe): Response;
    public function findUserByPostId(int $postId): ?User;
}