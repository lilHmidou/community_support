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
    private ?bool $Disability = null; // Correction du nom de la propriété

    public function getLevelStudies(): ?string
    {
        return $this->LevelStudies;
    }

    public function setLevelStudies(string $LevelStudies): static
    {
        $this->LevelStudies = $LevelStudies;

        return $this;
    }

    public function getDisability(): ?bool // Correction du nom de la méthode getter
    {
        return $this->Disability;
    }

    public function setDisability(bool $Disability): static
    {
        $this->Disability = $Disability;

        return $this;
    }
}