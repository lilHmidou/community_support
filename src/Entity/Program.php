<?php

namespace App\Entity;

use App\Repository\ProgramRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\Types\Types;
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
    private ?string $titleP = null;

    #[ORM\Column(length: 255)]
    private ?string $descriptionP = null;

    #[ORM\Column(length: 255)]
    private ?string $frequency = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private ?\DateTimeImmutable $CreatedAt_P = null;

    public function __construct()
    {
        $this->CreatedAt_P = new \DateTimeImmutable();
    }

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

    public function getCreatedAtP(): ?\DateTimeImmutable
    {
        return $this->CreatedAt_P;
    }

    public function setCreatedAtP(\DateTimeImmutable $CreatedAt_P): static
    {
        $this->CreatedAt_P = $CreatedAt_P;

        return $this;
    }

    #[ORM\PrePersist]
    public function setCreatedAtPValue(): void
    {
        $this->CreatedAt_P = new \DateTimeImmutable();
    }
}
