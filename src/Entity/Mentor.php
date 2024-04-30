<?php

namespace App\Entity;

use App\Repository\MentorRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: MentorRepository::class)]
class Mentor extends UserTutorat
{

    #[ORM\Column(length: 20)]
    #[Assert\NotBlank]
    #[Assert\Length(max: 20)]
    private ?string $levelExperience = null;

    #[ORM\Column(length: 50)]
    #[Assert\NotBlank]
    #[Assert\Length(max: 50)]
    private ?string $availability = null;

    #[ORM\OneToMany(mappedBy: 'mentor', targetEntity: Program::class)]
    private Collection $programs;

    public function __construct()
    {
        parent::__construct();
        $this->programs = new ArrayCollection();
    }

    public function getLevelExperience(): ?string
    {
        return $this->levelExperience;
    }

    public function setLevelExperience(string $levelExperience): static
    {
        $this->levelExperience = $levelExperience;

        return $this;
    }

    public function getAvailability(): ?string
    {
        return $this->availability;
    }

    public function setAvailability(string $availability): static
    {
        $this->availability = $availability;

        return $this;
    }

    /**
     * @return Collection|Program[]
     */
    public function getMentorPrograms(): Collection
    {
        return $this->programs;
    }

    public function addMentorProgram(Program $program): static
    {
        if (!$this->programs->contains($program)) {
            $this->programs[] = $program;
            $program->setMentor($this);
        }

        return $this;
    }

    public function removeMentorProgram(Program $program): static
    {
        if ($this->programs->removeElement($program)) {
            // set the owning side to null (unless already changed)
            if ($program->getMentor() === $this) {
                $program->setMentor(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        $user = $this->getUser();
        $fullName = $user->getFirstName() . ' ' . $user->getLastName();
        return $fullName. ' - ' . parent::__toString() . ' - ' . $this->levelExperience . ' - ' . $this->availability;
    }

}
