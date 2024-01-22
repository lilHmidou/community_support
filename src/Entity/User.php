<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\HasLifecycleCallbacks]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $FirstName = null;

    #[ORM\Column(length: 50)]
    private ?string $LastName = null;

    #[ORM\Column(length: 50)]
    private ?string $Email = null;

    #[ORM\Column(length: 100)]
    private ?string $Password = null;

    #[ORM\Column(length: 150)]
    private ?string $Adress = null;

    #[ORM\Column(length: 15)]
    private ?string $Phone = null;

    #[ORM\Column(length: 10)]
    private ?string $Gender = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $DOB = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private ?\DateTimeImmutable $CreatedAt_U = null;

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

    public function getEmail(): ?string
    {
        return $this->Email;
    }

    public function setEmail(string $Email): static
    {
        $this->Email = $Email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->Password;
    }

    public function setPassword(string $Password): static
    {
        $this->Password = $Password;

        return $this;
    }

    public function getAdress(): ?string
    {
        return $this->Adress;
    }

    public function setAdress(string $Adress): static
    {
        $this->Adress = $Adress;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->Phone;
    }

    public function setPhone(string $Phone): static
    {
        $this->Phone = $Phone;

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
}
