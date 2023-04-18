<?php

namespace App\Entity;

use App\Repository\MagasinRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;

use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: MagasinRepository::class)]
class Magasin
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['magasin:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['magasin:read'])]
    private ?string $nomMag = null;

    #[ORM\Column(length: 255)]
    #[Groups(['magasin:read'])]
    private ?string $nomSocial = null;

    #[ORM\Column(length: 255)]
    #[Groups(['magasin:read'])]
    private ?string $rue = null;

    #[ORM\Column(length: 255)]
    #[Groups(['magasin:read'])]
    private ?string $ville = null;

    #[ORM\Column(length: 255)]
    #[Groups(['magasin:read'])]
    private ?string $fax = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Groups(['magasin:read'])]
    private ?\DateTimeInterface $dateCreation = null;

    #[ORM\Column(length: 255)]
    #[Groups(['magasin:read'])]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    #[Groups(['magasin:read'])]
    private ?string $nomRue = null;

    #[ORM\Column(length: 255)]
    #[Groups(['magasin:read'])]
    private ?string $phoneFix = null;

    #[ORM\Column(length: 255)]
    #[Groups(['magasin:read'])]
    private ?string $dette = null;

    #[ORM\Column(length: 255)]
    #[Groups(['magasin:read'])]
    private ?string $white = null;

    #[ORM\OneToMany(mappedBy: 'magasin', targetEntity: Sav::class)]
    private Collection $savs;

    #[ORM\OneToMany(mappedBy: 'magasin', targetEntity: Produit::class)]
    private Collection $produits;

    public function __construct()
    {
        $this->savs = new ArrayCollection();
        $this->produits = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomMag(): ?string
    {
        return $this->nomMag;
    }

    public function setNomMag(string $nomMag): self
    {
        $this->nomMag = $nomMag;

        return $this;
    }

    public function getNomSocial(): ?string
    {
        return $this->nomSocial;
    }

    public function setNomSocial(string $nomSocial): self
    {
        $this->nomSocial = $nomSocial;

        return $this;
    }

    public function getRue(): ?string
    {
        return $this->rue;
    }

    public function setRue(string $rue): self
    {
        $this->rue = $rue;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(string $ville): self
    {
        $this->ville = $ville;

        return $this;
    }

    public function getFax(): ?string
    {
        return $this->fax;
    }

    public function setFax(string $fax): self
    {
        $this->fax = $fax;

        return $this;
    }

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->dateCreation;
    }

    public function setDateCreation(\DateTimeInterface $dateCreation): self
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getNomRue(): ?string
    {
        return $this->nomRue;
    }

    public function setNomRue(string $nomRue): self
    {
        $this->nomRue = $nomRue;

        return $this;
    }

    public function getPhoneFix(): ?string
    {
        return $this->phoneFix;
    }

    public function setPhoneFix(string $phoneFix): self
    {
        $this->phoneFix = $phoneFix;

        return $this;
    }

    public function getDette(): ?string
    {
        return $this->dette;
    }

    public function setDette(string $dette): self
    {
        $this->dette = $dette;

        return $this;
    }

    public function getWhite(): ?string
    {
        return $this->white;
    }

    public function setWhite(string $white): self
    {
        $this->white = $white;

        return $this;
    }

    /**
     * @return Collection<int, Sav>
     */
    public function getSavs(): Collection
    {
        return $this->savs;
    }

    public function addSav(Sav $sav): self
    {
        if (!$this->savs->contains($sav)) {
            $this->savs->add($sav);
            $sav->setMagasin($this);
        }

        return $this;
    }

    public function removeSav(Sav $sav): self
    {
        if ($this->savs->removeElement($sav)) {
            // set the owning side to null (unless already changed)
            if ($sav->getMagasin() === $this) {
                $sav->setMagasin(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Produit>
     */
    public function getProduits(): Collection
    {
        return $this->produits;
    }

    public function addProduit(Produit $produit): self
    {
        if (!$this->produits->contains($produit)) {
            $this->produits->add($produit);
            $produit->setMagasin($this);
        }

        return $this;
    }

    public function removeProduit(Produit $produit): self
    {
        if ($this->produits->removeElement($produit)) {
            // set the owning side to null (unless already changed)
            if ($produit->getMagasin() === $this) {
                $produit->setMagasin(null);
            }
        }

        return $this;
    }
}
