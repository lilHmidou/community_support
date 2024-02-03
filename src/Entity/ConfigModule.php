<?php

namespace App\Entity;

use App\Repository\ConfigModuleRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ConfigModuleRepository::class)]
class ConfigModule
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?bool $Is_mentor_enabled = null;

    #[ORM\Column]
    private ?bool $Is_etudiant_enabled = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function isIsMentorEnabled(): ?bool
    {
        return $this->Is_mentor_enabled;
    }

    public function setIsMentorEnabled(bool $Is_mentor_enabled): static
    {
        $this->Is_mentor_enabled = $Is_mentor_enabled;

        return $this;
    }

    public function isIsEtudiantEnabled(): ?bool
    {
        return $this->Is_etudiant_enabled;
    }

    public function setIsEtudiantEnabled(bool $Is_etudiant_enabled): static
    {
        $this->Is_etudiant_enabled = $Is_etudiant_enabled;

        return $this;
    }
}
