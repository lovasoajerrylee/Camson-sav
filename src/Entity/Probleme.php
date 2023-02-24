<?php

namespace App\Entity;

use App\Repository\ProblemeRepository;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ProblemeRepository::class)]
class Probleme
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['probleme:read'])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'problemes')]
    private ?Sav $sav = null;

    #[ORM\Column(length: 255)]
    #[Groups(['probleme:read'])]
    private ?string $probleme = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSav(): ?Sav
    {
        return $this->sav;
    }

    public function setSav(?Sav $sav): self
    {
        $this->sav = $sav;

        return $this;
    }

    public function getProbleme(): ?string
    {
        return $this->probleme;
    }

    public function setProbleme(string $probleme): self
    {
        $this->probleme = $probleme;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }
}
