<?php

namespace App\Entity;

use App\Repository\EtudiantRepository;
use App\security\Role;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

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
    #[Assert\NotBlank]
    #[Assert\Length(max: 30)]
    private ?string $levelStudies = null;

    #[ORM\Column]
    private ?bool $disability = null;

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
        return $this->levelStudies;
    }

    public function setLevelStudies(string $levelStudies): static
    {
        $this->levelStudies = $levelStudies;

        return $this;
    }

    public function getDisability(): ?bool // Correction du nom de la méthode getter
    {
        return $this->disability;
    }

    public function setDisability(bool $disability): static
    {
        $this->disability = $disability;

        return $this;
    }

    public function __toString(): string
    {
        return parent::__toString() . ' - ' . $this->levelStudies . ' - ' . ($this->disability ? 'Oui' : 'Non');
    }

    public function isDisability(): ?bool
    {
        return $this->disability;
    }

}