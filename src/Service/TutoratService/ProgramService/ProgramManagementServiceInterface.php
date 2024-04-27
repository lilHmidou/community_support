<?php

namespace App\Service\TutoratService\ProgramService;

use App\Entity\Mentor;
use App\Entity\Program;
use App\Entity\User;
use Doctrine\Common\Collections\Collection;

interface ProgramManagementServiceInterface
{
    public function getProgramById(int $id): ?Program;
    public function getUserPrograms(User $user): ?Collection;
    public function saveProgram(Program $program): void;
    public function removeProgram(Program $program): void;
    public function assignMentorToProgram(Program $program): void;
    public function removeMentorPrograms(Mentor $mentor): void;
}