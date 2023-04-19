<?php

namespace App\Entity;

use App\Repository\ProduitRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ProduitRepository::class)]
class Produit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['produit:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['produit:read'])]
    private ?string $nomProduit = null;

    #[ORM\Column(length: 255)]
    #[Groups(['produit:read'])]
    private ?string $referenceProduit = null;

    #[ORM\ManyToOne(inversedBy: 'produits')]
    #[Groups(['produit:read'])]
    private ?Magasin $magasin = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomProduit(): ?string
    {
        return $this->nomProduit;
    }

    public function setNomProduit(string $nomProduit): self
    {
        $this->nomProduit = $nomProduit;

        return $this;
    }

    public function getReferenceProduit(): ?string
    {
        return $this->referenceProduit;
    }

    public function setReferenceProduit(string $referenceProduit): self
    {
        $this->referenceProduit = $referenceProduit;

        return $this;
    }

    public function getMagasin(): ?Magasin
    {
        return $this->magasin;
    }

    public function setMagasin(?Magasin $magasin): self
    {
        $this->magasin = $magasin;

        return $this;
    }
}
