<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\ProduitsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProduitsRepository::class)]
#[ApiResource]
class Produits
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $nom;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $slug;

    #[ORM\Column(type: 'text', nullable: true)]
    private $description;

    #[ORM\ManyToOne(targetEntity: Categories::class, inversedBy: 'produits')]
    private $categorie;


   
    
  
    #[ORM\OneToMany(mappedBy: 'produit', targetEntity: Files::class, cascade: ['all'])]
    private $files;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $prix;

    #[ORM\OneToMany(mappedBy: 'produit', targetEntity: Commandes::class)]
    private $commandes;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private $accueil;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private $dispo;

    #[ORM\OneToMany(mappedBy: 'produits', targetEntity: Videos::class)]
    private $videos;

    public function __construct()
    {
        $this->files = new ArrayCollection();
        $this->commandes = new ArrayCollection();
        $this->videos = new ArrayCollection();
    }

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

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getCategorie(): ?Categories
    {
        return $this->categorie;
    }

    public function setCategorie(?Categories $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }

    /**
     * @return Collection<int, Files>
     */
    public function getFiles(): Collection
    {
        return $this->files;
    }

    public function addFile(Files $file): self
    {
        if (!$this->files->contains($file)) {
            $this->files[] = $file;
            $file->setProduit($this);
        }

        return $this;
    }

    public function removeFile(Files $file): self
    {
        if ($this->files->removeElement($file)) {
            // set the owning side to null (unless already changed)
            if ($file->getProduit() === $this) {
                $file->setProduit(null);
            }
        }

        return $this;
    }

    public function getPrix(): ?string
    {
        return $this->prix;
    }

    public function setPrix(?string $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    /**
     * @return Collection<int, Commandes>
     */
    public function getCommandes(): Collection
    {
        return $this->commandes;
    }

    public function addCommande(Commandes $commande): self
    {
        if (!$this->commandes->contains($commande)) {
            $this->commandes[] = $commande;
            $commande->setProduit($this);
        }

        return $this;
    }

    public function removeCommande(Commandes $commande): self
    {
        if ($this->commandes->removeElement($commande)) {
            // set the owning side to null (unless already changed)
            if ($commande->getProduit() === $this) {
                $commande->setProduit(null);
            }
        }

        return $this;
    }

    public function isAccueil(): ?bool
    {
        return $this->accueil;
    }

    public function setAccueil(?bool $accueil): self
    {
        $this->accueil = $accueil;

        return $this;
    }

    public function isDispo(): ?bool
    {
        return $this->dispo;
    }

    public function setDispo(?bool $dispo): self
    {
        $this->dispo = $dispo;

        return $this;
    }

    /**
     * @return Collection<int, Videos>
     */
    public function getVideos(): Collection
    {
        return $this->videos;
    }

    public function addVideo(Videos $video): self
    {
        if (!$this->videos->contains($video)) {
            $this->videos[] = $video;
            $video->setProduits($this);
        }

        return $this;
    }

    public function removeVideo(Videos $video): self
    {
        if ($this->videos->removeElement($video)) {
            // set the owning side to null (unless already changed)
            if ($video->getProduits() === $this) {
                $video->setProduits(null);
            }
        }

        return $this;
    }
}
