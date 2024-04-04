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
    private ?\DateTimeInterface $createdAt_CM = null;

    #[ORM\Column(length: 30)]
    private ?string $topic = null;

    #[ORM\Column(length: 255)]
    private ?string $contentCM = null;

    #[ORM\ManyToOne(inversedBy: 'ContactMessage')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    public function __construct()
    {
        $this->createdAt_CM = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedAtCM(): ?\DateTimeInterface
    {
        return $this->createdAt_CM;
    }

    public function setCreatedAtCM(\DateTimeInterface $createdAt_CM): static
    {
        $this->createdAt_CM = $createdAt_CM;

        return $this;
    }

    public function getTopic(): ?string
    {
        return $this->topic;
    }

    public function setTopic(string $topic): static
    {
        $this->topic = $topic;

        return $this;
    }

    public function getContentCM(): ?string
    {
        return $this->contentCM;
    }

    public function setContentCM(string $contentCM): static
    {
        $this->contentCM = $contentCM;

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
