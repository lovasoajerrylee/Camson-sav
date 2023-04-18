<?php

namespace App\Entity;

use App\Repository\VenteRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VenteRepository::class)]
class Vente
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::ARRAY)]
    private array $produit = [];

    #[ORM\Column]
    private ?int $qttVente = null;

    #[ORM\Column]
    private ?int $prix = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateVente = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProduit(): array
    {
        return $this->produit;
    }

    public function setProduit(array $produit): self
    {
        $this->produit = $produit;

        return $this;
    }

    public function getQttVente(): ?int
    {
        return $this->qttVente;
    }

    public function setQttVente(int $qttVente): self
    {
        $this->qttVente = $qttVente;

        return $this;
    }

    public function getPrix(): ?int
    {
        return $this->prix;
    }

    public function setPrix(int $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getDateVente(): ?\DateTimeInterface
    {
        return $this->dateVente;
    }

    public function setDateVente(\DateTimeInterface $dateVente): self
    {
        $this->dateVente = $dateVente;

        return $this;
    }
}
