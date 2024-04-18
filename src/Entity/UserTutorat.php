<?php

namespace App\Entity;

use App\Repository\UserTutoratRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\DiscriminatorColumn;
use Doctrine\ORM\Mapping\DiscriminatorMap;
use Doctrine\ORM\Mapping\InheritanceType;
use Symfony\Component\Validator\Constraints as Assert;

//Heritage
#[InheritanceType('JOINED')]
#[DiscriminatorColumn(name: 'type', type: 'string')]
#[DiscriminatorMap(['mentor' => Mentor::class, 'tutorat' => Etudiant::class])]

#[ORM\Entity(repositoryClass: UserTutoratRepository::class)]
class UserTutorat
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 30)]
    #[Assert\NotBlank]
    #[Assert\Length(max: 30)]
    private ?string $domain = null;

    #[ORM\Column(length: 30)]
    #[Assert\NotBlank]
    #[Assert\Length(max: 30)]
    private ?string $learningChoice = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    private ?string $comments = null;

    #[ORM\ManyToOne(inversedBy: 'UserTutorat')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column(type : 'string', length: 255, nullable: false)]
    private ?string $docPath = null;

    public function __construct()
    {

    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDomain(): ?string
    {
        return $this->domain;
    }

    public function setDomain(string $domain): static
    {
        $this->domain = $domain;

        return $this;
    }

    public function getLearningChoice(): ?string
    {
        return $this->learningChoice;
    }

    public function setLearningChoice(string $learningChoice): static
    {
        $this->learningChoice = $learningChoice;

        return $this;
    }

    public function getComments(): ?string
    {
        return $this->comments;
    }

    public function setComments(string $comments): static
    {
        $this->comments = $comments;

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

    public function getDocPath(): ?string
    {
        return $this->docPath;
    }

    public function setDocPath(string $docPath): static
    {
        $this->docPath = $docPath;

        return $this;
    }

    public function __toString(): string
    {
        return $this->domain . ' ' . $this->learningChoice . ' ' . $this->comments . ' ' . $this->docPath;
    }
}
