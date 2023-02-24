<?php

namespace App\Entity;

use App\Repository\ResponsableRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ResponsableRepository::class)]
class Responsable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nomRespo = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $telRespo1 = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $telRespo2 = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomRespo(): ?string
    {
        return $this->nomRespo;
    }

    public function setNomRespo(string $nomRespo): self
    {
        $this->nomRespo = $nomRespo;

        return $this;
    }

    public function getTelRespo1(): ?string
    {
        return $this->telRespo1;
    }

    public function setTelRespo1(?string $telRespo1): self
    {
        $this->telRespo1 = $telRespo1;

        return $this;
    }

    public function getTelRespo2(): ?string
    {
        return $this->telRespo2;
    }

    public function setTelRespo2(?string $telRespo2): self
    {
        $this->telRespo2 = $telRespo2;

        return $this;
    }
}
