<?php

namespace App\Entity;

use App\Repository\ProgramRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProgramRepository::class)]
class Program
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id')]
    private ?User $user;


    #[ORM\Column(length: 255)]
    private ?string $title_p = null;

    #[ORM\Column(length: 255)]
    private ?string $description_p = null;

    #[ORM\Column(length: 255)]
    private ?string $frequency = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getTitleP(): ?string
    {
        return $this->title_p;
    }

    public function setTitleP(string $title_p): static
    {
        $this->title_p = $title_p;

        return $this;
    }

    public function getDescriptionP(): ?string
    {
        return $this->description_p;
    }

    public function setDescriptionP(string $description_p): static
    {
        $this->description_p = $description_p;

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
}
