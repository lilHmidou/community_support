<?php

namespace App\Entity;

use App\Repository\MentorRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MentorRepository::class)]
class Mentor extends UserTutorat
{

    #[ORM\Column(length: 20)]
    private ?string $LevelExperience = null;

    #[ORM\Column(length: 50)]
    private ?string $Avaibility = null;

    public function getLevelExperience(): ?string
    {
        return $this->LevelExperience;
    }

    public function setLevelExperience(string $LevelExperience): static
    {
        $this->LevelExperience = $LevelExperience;

        return $this;
    }

    public function getAvaibility(): ?string
    {
        return $this->Avaibility;
    }

    public function setAvaibility(string $Avaibility): static
    {
        $this->Avaibility = $Avaibility;

        return $this;
    }
}
