<?php

namespace App\Entity;

use App\Repository\MentorRepository;
use App\security\Role;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: MentorRepository::class)]
class Mentor extends UserTutorat
{

    #[ORM\Column(length: 20)]
    #[Assert\NotBlank]
    #[Assert\Length(max: 20)]
    private ?string $levelExperience = null;

    #[ORM\Column(length: 50)]
    #[Assert\NotBlank]
    #[Assert\Length(max: 50)]
    private ?string $availability = null;

    public function __construct()
    {
        // Récupération de l'utilisateur associé à l'entité UserTutorat
        $user = $this->getUser();

        // Ajout automatique du rôle ROLE_ETUDIANT à l'utilisateur
        $user->addRole(Role::ROLE_MENTOR);
    }

    public function getLevelExperience(): ?string
    {
        return $this->levelExperience;
    }

    public function setLevelExperience(string $levelExperience): static
    {
        $this->levelExperience = $levelExperience;

        return $this;
    }

    public function getAvailability(): ?string
    {
        return $this->availability;
    }

    public function setAvailability(string $availability): static
    {
        $this->availability = $availability;

        return $this;
    }

    public function __toString(): string
    {
        return parent::__toString() . ' - ' . $this->levelExperience . ' - ' . $this->availability;
    }

}
