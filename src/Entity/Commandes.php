<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\CommandesRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommandesRepository::class)]
#[ApiResource]
class Commandes
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $nom;

    #[ORM\Column(type: 'string', length: 255)]
    private $telephone;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $etage;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $couleur;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $dimentions;

    #[ORM\Column(type: 'string', length: 255)]
    private $Email;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getEtage(): ?string
    {
        return $this->etage;
    }

    public function setEtage(?string $etage): self
    {
        $this->etage = $etage;

        return $this;
    }

    public function getCouleur(): ?string
    {
        return $this->couleur;
    }

    public function setCouleur(?string $couleur): self
    {
        $this->couleur = $couleur;

        return $this;
    }

    public function getDimentions(): ?string
    {
        return $this->dimentions;
    }

    public function setDimentions(?string $dimentions): self
    {
        $this->dimentions = $dimentions;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->Email;
    }

    public function setEmail(string $Email): self
    {
        $this->Email = $Email;

        return $this;
    }
}
