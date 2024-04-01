<?php

namespace App\Entity;

use App\Repository\PostRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PostRepository::class)]
class Post
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $Title = null;

    #[ORM\Column(length: 255)]
    private ?string $Description = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private ?\DateTimeImmutable $CreatedAt_P = null;

    #[ORM\Column(length: 50)]
    private ?string $Location = null;

    #[ORM\Column(length: 30)]
    private ?string $Category = null;

    #[ORM\Column(type: 'integer')]
    private ?int $Nb_Like = null;


    #[ORM\OneToMany(mappedBy: 'post', targetEntity: Message::class)]
    private Collection $Message;

    /**
     * @ORM\OneToMany(targetEntity=PostLike::class, mappedBy="post")
     */
    private $likes;

    // Autres méthodes de l'entité...

    /**
     * @return Collection|Like[]
     */
    public function getLikes(): Collection
    {
        return $this->likes;
    }


    public function __construct()
    {
        $this->Message = new ArrayCollection();
        $this->CreatedAt_P = new \DateTimeImmutable();
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

    /**
     * @return Collection<int, Message>
     */
    public function getMessage(): Collection
    {
        return $this->Message;
    }

    public function addMessage(Message $message): static
    {
        if (!$this->Message->contains($message)) {
            $this->Message->add($message);
            $message->setPost($this);
        }

        return $this;
    }

    public function removeMessage(Message $message): static
    {
        if ($this->Message->removeElement($message)) {
            // set the owning side to null (unless already changed)
            if ($message->getPost() === $this) {
                $message->setPost(null);
            }
        }

        return $this;
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


}
