<?php

namespace App\Entity;

use App\Repository\EtudiantRepository;
use App\security\Role;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EtudiantRepository::class)]
class Etudiant extends UserTutorat
{
    #[ORM\Column(length: 30)]
    private ?string $LevelStudies = null;

    #[ORM\Column]
    private ?bool $Disability = null;

    public function __construct()
    {
        // Récupération de l'utilisateur associé à l'entité UserTutorat
        $user = $this->getUser();

        // Ajout automatique du rôle ROLE_ETUDIANT à l'utilisateur
        $user->addRole(Role::ROLE_ETUDIANT);
    }

    public function getLevelStudies(): ?string
    {
        return $this->LevelStudies;
    }

    public function setLevelStudies(string $LevelStudies): static
    {
        $this->LevelStudies = $LevelStudies;

        return $this;
    }

    public function getDisability(): ?bool // Correction du nom de la méthode getter
    {
        return $this->Disability;
    }

    public function setDisability(bool $Disability): static
    {
        $this->Disability = $Disability;

        return $this;
    }

    public function __toString(): string
    {
        return parent::__toString() . ' - ' . $this->LevelStudies . ' - ' . ($this->Disability ? 'Oui' : 'Non');
    }

}