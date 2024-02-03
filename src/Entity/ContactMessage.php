<?php

namespace App\Entity;

use App\Repository\ContactMessageRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ContactMessageRepository::class)]
class ContactMessage
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $CreatedAt_CM = null;

    #[ORM\Column(length: 30)]
    private ?string $Topic = null;

    #[ORM\Column(length: 255)]
    private ?string $ContentCM = null;

    #[ORM\ManyToOne(inversedBy: 'ContactMessage')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedAtCM(): ?\DateTimeInterface
    {
        return $this->CreatedAt_CM;
    }

    public function setCreatedAtCM(\DateTimeInterface $CreatedAt_CM): static
    {
        $this->CreatedAt_CM = $CreatedAt_CM;

        return $this;
    }

    public function getTopic(): ?string
    {
        return $this->Topic;
    }

    public function setTopic(string $Topic): static
    {
        $this->Topic = $Topic;

        return $this;
    }

    public function getContentCM(): ?string
    {
        return $this->ContentCM;
    }

    public function setContentCM(string $ContentCM): static
    {
        $this->ContentCM = $ContentCM;

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
