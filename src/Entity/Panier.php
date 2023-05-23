<?php

namespace App\Entity;

use App\Repository\PanierRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PanierRepository::class)]
class Panier
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['panier:read'])]
    private ?int $id = null;

    #[Groups(['panier:read'])]
    #[ORM\ManyToOne(inversedBy: 'paniers')]
    private ?Client $Client = null;

    #[Groups(['panier:read'])]
    #[ORM\ManyToOne(inversedBy: 'paniers')]
    private ?Magasin $Magazin = null;

    #[Groups(['panier:read'])]
    #[ORM\ManyToOne(inversedBy: 'paniers')]
    private ?Produit $Produit = null;

    #[Groups(['panier:read'])]
    #[ORM\Column]
    private ?int $Quantite = null;

    #[Groups(['panier:read'])]
    #[ORM\Column]
    private ?int $PrixUnitaire = null;

    #[Groups(['panier:read'])]
    #[ORM\Column]
    private ?int $SousTotal = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getClient(): ?Client
    {
        return $this->Client;
    }

    public function setClient(?Client $Client): self
    {
        $this->Client = $Client;

        return $this;
    }

    public function getMagazin(): ?Magasin
    {
        return $this->Magazin;
    }

    public function setMagazin(?Magasin $Magazin): self
    {
        $this->Magazin = $Magazin;

        return $this;
    }

    public function getProduit(): ?Produit
    {
        return $this->Produit;
    }

    public function setProduit(?Produit $Produit): self
    {
        $this->Produit = $Produit;

        return $this;
    }

    public function getQuantite(): ?int
    {
        return $this->Quantite;
    }

    public function setQuantite(int $Quantite): self
    {
        $this->Quantite = $Quantite;

        return $this;
    }

    public function getPrixUnitaire(): ?int
    {
        return $this->PrixUnitaire;
    }

    public function setPrixUnitaire(int $PrixUnitaire): self
    {
        $this->PrixUnitaire = $PrixUnitaire;

        return $this;
    }

    public function getSousTotal(): ?int
    {
        return $this->SousTotal;
    }

    public function setSousTotal(int $SousTotal): self
    {
        $this->SousTotal = $SousTotal;

        return $this;
    }
}
