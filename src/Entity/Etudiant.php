<?php

namespace App\Entity;

use App\Repository\EtudiantRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EtudiantRepository::class)]
class Etudiant extends UserTutorat
{

    #[ORM\Column(length: 30)]
    private ?string $LevelStudies = null;

    #[ORM\Column]
    private ?bool $Disabilty = null;

    public function getLevelStudies(): ?string
    {
        return $this->LevelStudies;
    }

    public function setLevelStudies(string $LevelStudies): static
    {
        $this->LevelStudies = $LevelStudies;

        return $this;
    }

    public function isDisabilty(): ?bool
    {
        return $this->Disabilty;
    }

    public function setDisabilty(bool $Disabilty): static
    {
        $this->Disabilty = $Disabilty;

        return $this;
    }
}
