<?php

namespace App\Entity;

use App\Repository\MessageRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: MessageRepository::class)]
class Message
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    #[Assert\NotNull]
    private ?\DateTimeImmutable $SendAt = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(
        max: 255,
        maxMessage: "Le contenu du message ne peut pas être plus long que {{ limit }} caractères."
    )]
    private ?string $ContentM = null;

    #[ORM\ManyToOne(inversedBy: 'Message')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull]
    private ?Post $post = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSendAt(): ?\DateTimeImmutable
    {
        return $this->SendAt;
    }

    public function setSendAt(\DateTimeImmutable $SendAt): static
    {
        $this->SendAt = $SendAt;

        return $this;
    }

    public function getContentM(): ?string
    {
        return $this->ContentM;
    }

    public function setContentM(string $ContentM): static
    {
        $this->ContentM = $ContentM;

        return $this;
    }

    public function getPost(): ?Post
    {
        return $this->post;
    }

    public function setPost(?Post $post): static
    {
        $this->post = $post;

        return $this;
    }
}
