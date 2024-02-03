<?php

namespace App\Entity;

use App\Repository\UserTutoratRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\DiscriminatorColumn;
use Doctrine\ORM\Mapping\DiscriminatorMap;
use Doctrine\ORM\Mapping\InheritanceType;

//Heritage
#[InheritanceType('JOINED')]
#[DiscriminatorColumn(name: 'type', type: 'string')]
#[DiscriminatorMap(['mentor' => Mentor::class, 'etudiant' => Etudiant::class])]

#[ORM\Entity(repositoryClass: UserTutoratRepository::class)]
class UserTutorat
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 30)]
    private ?string $Domain = null;

    #[ORM\Column(length: 30)]
    private ?string $LearningChoice = null;

    #[ORM\Column(length: 255)]
    private ?string $Comments = null;

    #[ORM\ManyToOne(inversedBy: 'UserTutorat')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDomain(): ?string
    {
        return $this->Domain;
    }

    public function setDomain(string $Domain): static
    {
        $this->Domain = $Domain;

        return $this;
    }

    public function getLearningChoice(): ?string
    {
        return $this->LearningChoice;
    }

    public function setLearningChoice(string $LearningChoice): static
    {
        $this->LearningChoice = $LearningChoice;

        return $this;
    }

    public function getComments(): ?string
    {
        return $this->Comments;
    }

    public function setComments(string $Comments): static
    {
        $this->Comments = $Comments;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }
}
