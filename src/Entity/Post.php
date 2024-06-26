<?php

namespace App\Entity;

use App\Repository\PostRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: PostRepository::class)]
class Post
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    #[Assert\NotBlank]
    #[Assert\Length(max: 50)]
    private ?string $Title = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    private ?string $Description = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    private ?string $meetingAddress = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    #[Assert\NotBlank]
    private ?\DateTimeImmutable $eventDate = null;

    #[ORM\Column(length: 50)]
    #[Assert\NotBlank]
    #[Assert\Length(max: 50)]
    private ?string $startTime = null;


    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    #[Assert\NotBlank]
    private ?\DateTimeImmutable $createdAtPost = null;

    #[ORM\Column(length: 50)]
    #[Assert\Length(max: 50)]
    private ?string $Location = null;

    #[ORM\Column(length: 30)]
    #[Assert\Length(max: 30)]
    private ?string $Category = null;

    #[ORM\Column(type: 'integer')]
    private ?int $Nb_Like = null;

    /**
     * @ORM\OneToMany(targetEntity=PostLike::class, mappedBy="post")
     */
    private $likes;

    /**
     * @return Collection|Like[]
     */
    public function getLikes(): Collection
    {
        return $this->likes;
    }


    public function __construct()
    {
        $this->createdAtPost = new \DateTimeImmutable();
        $this->likes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->Title;
    }

    public function setTitle(string $Title): static
    {
        $this->Title = $Title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->Description;
    }

    public function setDescription(string $Description): static
    {
        $this->Description = $Description;

        return $this;
    }

    public function getMeetingAddress(): ?string
    {
        return $this->meetingAddress;
    }

    public function setMeetingAddress(?string $meetingAddress): static
    {
        $this->meetingAddress = $meetingAddress;

        return $this;
    }

    public function getEventDate(): ?\DateTimeInterface
    {
        return $this->eventDate;
    }

    public function setEventDate(\DateTimeImmutable $eventDate): static
    {
        $this->eventDate = $eventDate;

        return $this;
    }

    public function getStartTime(): ?String
    {
        return $this->startTime;
    }

    public function setStartTime(String $startTime): static
    {
        $this->startTime = $startTime;

        return $this;
    }

    public function getCreatedAtPost(): ?\DateTimeImmutable
    {
        return $this->createdAtPost;
    }

    public function setCreatedAtPost(\DateTimeImmutable $createdAtPost): static
    {
        $this->createdAtPost = $createdAtPost;

        return $this;
    }

    #[ORM\PrePersist]
    public function setCreatedAtPostValue(): void
    {
        $this->createdAtPost = new \DateTimeImmutable();
    }

    public function getLocation(): ?string
    {
        return $this->Location;
    }

    public function setLocation(string $Location): static
    {
        $this->Location = $Location;

        return $this;
    }

    public function getCategory(): ?string
    {
        return $this->Category;
    }

    public function setCategory(string $Category): static
    {
        $this->Category = $Category;

        return $this;
    }

    public function getLike(): ?int
    {
        return $this->Nb_Like;
    }

    // Setter pour l'attribut "like"
    public function setLike(int $Nb_Like): self
    {
        $this->Nb_Like = $Nb_Like;

        return $this;
    }

    public function __toString(): string
    {
        return "Title: " . $this->Title . ", Description: " . $this->Description . ", Location: " . $this->Location . ", Category: " . $this->Category . ", Created At: " . $this->CreatedAt_Post->format('Y-m-d H:i:s');
    }


    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id')]
    private ?User $user;

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getNbLike(): ?int
    {
        return $this->Nb_Like;
    }

    public function setNbLike(int $Nb_Like): static
    {
        $this->Nb_Like = $Nb_Like;

        return $this;
    }


}
