<?php

namespace App\Entity;

use App\Repository\ListProblemeRepository;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ListProblemeRepository::class)]
class ListProbleme
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['ListeProbleme:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['ListeProbleme:read'])]
    private ?string $nom = null;

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
}
