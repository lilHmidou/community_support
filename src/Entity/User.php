<?php

namespace App\Entity;

use App\Repository\UserRepository;
use App\security\Role;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'L\'adresse e-mail existe déjà')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    #[Assert\NotBlank]
    private ?string $FirstName = null;

    #[ORM\Column(length: 50)]
    #[Assert\NotBlank]
    private ?string $LastName = null;

    #[ORM\Column(length: 150)]
    #[Assert\NotBlank]
    private ?string $Address = null;

    #[ORM\Column(length: 15)]
    #[Assert\NotBlank]
    private ?string $PhoneNumber = null;

    #[ORM\Column(length: 10)]
    #[Assert\NotBlank]
    private ?string $Gender = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Assert\NotBlank]
    #[Assert\Email]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\NotBlank]
    private ?\DateTime $DOB = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    #[Assert\NotBlank]
    private ?\DateTimeImmutable $CreatedAt_U = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: ContactMessage::class, orphanRemoval: true)]
    private Collection $ContactMessage;

    #[ORM\OneToOne(mappedBy: 'user', targetEntity: UserTutorat::class, cascade: ['persist', 'remove'])]
    private ?UserTutorat $UserTutorat = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Post::class, orphanRemoval: true)]
    private Collection $Post;

    public function __construct()
    {
        $this->ContactMessage = new ArrayCollection();
        $this->Post = new ArrayCollection();
        $this->roles = [Role::ROLE_USER];
        $this->CreatedAt_U = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->FirstName;
    }

    public function setFirstName(string $FirstName): static
    {
        $this->FirstName = $FirstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->LastName;
    }

    public function setLastName(string $LastName): static
    {
        $this->LastName = $LastName;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->Address;
    }

    public function setAddress(string $Address): static
    {
        $this->Address = $Address;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->PhoneNumber;
    }

    public function setPhoneNumber(string $PhoneNumber): static
    {
        $this->PhoneNumber = $PhoneNumber;

        return $this;
    }

    public function getGender(): ?string
    {
        return $this->Gender;
    }

    public function setGender(string $Gender): static
    {
        $this->Gender = $Gender;

        return $this;
    }

    public function getDOB(): ?\DateTime
    {
        return $this->DOB;
    }

    public function setDOB(\DateTime $DOB): static
    {
        $this->DOB = $DOB;

        return $this;
    }

    public function getCreatedAtU(): ?\DateTimeImmutable
    {
        return $this->CreatedAt_U;
    }

    public function setCreatedAtU(\DateTimeImmutable $CreatedAt_U): static
    {
        $this->CreatedAt_U = $CreatedAt_U;

        return $this;
    }

    #[ORM\PrePersist]
    public function setCreatedAtUValue(): void
    {
        $this->CreatedAt_U = new \DateTimeImmutable();
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
    * @see UserInterface
    */
    public function getRoles(): array
    {
        $roles = $this->roles;
        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    public function addRole(string $role): void
    {
        if (!in_array($role, $this->roles)) {
            $this->roles[] = $role;
        }
    }

    public function removeRole(string $role): void
    {
        $key = array_search($role, $this->roles, true);
        if ($key !== false) {
            unset($this->roles[$key]);
        }
    }

    public function hasRole(string $role): bool
    {
        return in_array($role, $this->roles, true);
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function __toString(): string
    {
        return $this->FirstName . ' ' . $this->LastName . ' - ' . $this->Address . ' - ' . $this->PhoneNumber . ' - ' . $this->Gender . ' - ' . $this->email;
    }

    public function setUserTutorat(?UserTutorat $UserTutorat): static
    {
        $this->UserTutorat = $UserTutorat;

        return $this;
    }

    public function getUserTutorat(): ?UserTutorat
    {
        return $this->UserTutorat;
    }

    /**
     * @return Collection<int, ContactMessage>
     */
    public function getContactMessage(): Collection
    {
        return $this->ContactMessage;
    }

    public function addContactMessage(ContactMessage $contactMessage): static
    {
        if (!$this->ContactMessage->contains($contactMessage)) {
            $this->ContactMessage->add($contactMessage);
            $contactMessage->setUser($this);
        }

        return $this;
    }

    public function removeContactMessage(ContactMessage $contactMessage): static
    {
        if ($this->ContactMessage->removeElement($contactMessage)) {
            // set the owning side to null (unless already changed)
            if ($contactMessage->getUser() === $this) {
                $contactMessage->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Post>
     */
    public function getPost(): Collection
    {
        return $this->Post;
    }

    public function addPost(Post $post): static
    {
        if (!$this->Post->contains($post)) {
            $this->Post->add($post);
            $post->setUser($this);
        }

        return $this;
    }

    public function removePost(Post $post): static
    {
        if ($this->Post->removeElement($post)) {
            // set the owning side to null (unless already changed)
            if ($post->getUser() === $this) {
                $post->setUser(null);
            }
        }
        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }
}
