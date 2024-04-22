<?php

namespace App\Entity;

use App\Repository\EtudiantRepository;
use App\security\Role;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: EtudiantRepository::class)]
class Etudiant extends UserTutorat
{
    #[ORM\ManyToMany(targetEntity: Program::class, inversedBy: 'etudiants')]
    #[ORM\JoinTable(name: 'student_program')]
    private Collection $programs;

    public function __construct()
    {
        parent::__construct();
        $this->programs = new ArrayCollection();
    }
    #[ORM\Column(length: 30)]
    private ?string $LevelStudies = null;

    #[ORM\Column]
    private ?bool $Disability = null;

    /**
     * @return Collection|Program[]
     */
    public function getEtudiantPrograms(): Collection
    {
        return $this->programs;
    }

    public function addEtudiantProgram(Program $program): static
    {
        if (!$this->programs->contains($program)) {
            $this->programs[] = $program;
            $program->addEtudiant($this);
        }

        return $this;
    }

    public function removeEtudiantProgram(Program $program): static
    {
        if ($this->programs->removeElement($program)) {
            $program->removeEtudiant($this);
        }

        return $this;
    }

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

    public function __toString(): string
    {
        return parent::__toString() . ' - ' . $this->LevelStudies . ' - ' . ($this->Disability ? 'Oui' : 'Non');
    }

    public function isDisability(): ?bool
    {
        return $this->Disability;
    }

}