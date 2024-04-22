<?php

namespace App\Entity;

use App\Repository\ProgramRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProgramRepository::class)]
class Program
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $titleP = null;

    #[ORM\Column(length: 255)]
    private ?string $descriptionP = null;

    #[ORM\Column(length: 255)]
    private ?string $frequency = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private ?\DateTimeImmutable $CreatedAt_Prog = null;

    #[ORM\ManyToMany(targetEntity: Etudiant::class, mappedBy: 'programs')]
    private Collection $etudiants;

    #[ORM\ManyToOne(targetEntity: Mentor::class, inversedBy: 'programs')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Mentor $mentor = null;

    public function __construct()
    {
        $this->etudiants = new ArrayCollection();
        $this->CreatedAt_Prog = new \DateTimeImmutable();
    }

    /**
     * @return Collection|Etudiant[]
     */
    public function getEtudiants(): Collection
    {
        return $this->etudiants;
    }

    public function addEtudiant(Etudiant $etudiant): self
    {
        if (!$this->etudiants->contains($etudiant)) {
            $this->etudiants[] = $etudiant;
            $etudiant->addEtudiantProgram($this);
        }
        return $this;
    }

    public function removeEtudiant(Etudiant $etudiant): static
    {
        if ($this->etudiants->removeElement($etudiant)) {
            $etudiant->removeProgram($this);
        }

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMentor(): ?Mentor
    {
        return $this->mentor;
    }

    public function setMentor(?Mentor $mentor): static
    {
        $this->mentor = $mentor;

        return $this;
    }

    public function getTitleP(): ?string
    {
        return $this->titleP;
    }

    public function setTitleP(string $titleP): static
    {
        $this->titleP = $titleP;

        return $this;
    }

    public function getDescriptionP(): ?string
    {
        return $this->descriptionP;
    }

    public function setDescriptionP(string $descriptionP): static
    {
        $this->descriptionP = $descriptionP;

        return $this;
    }

    public function getFrequency(): ?string
    {
        return $this->frequency;
    }

    public function setFrequency(string $frequency): static
    {
        $this->frequency = $frequency;

        return $this;
    }

    public function getCreatedAtProg(): ?\DateTimeImmutable
    {
        return $this->CreatedAt_Prog;
    }

    public function setCreatedAtProg(\DateTimeImmutable $CreatedAt_Prog): static
    {
        $this->CreatedAt_Prog = $CreatedAt_Prog;

        return $this;
    }

    #[ORM\PrePersist]
    public function setCreatedAtProgValue(): void
    {
        $this->CreatedAt_Prog = new \DateTimeImmutable();
    }

}
