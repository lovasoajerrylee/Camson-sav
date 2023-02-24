<?php

namespace App\Entity;

use App\Repository\PrioritaireRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: PrioritaireRepository::class)]
class Prioritaire
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['prioritaire:read'])]
    private ?int $id = null;

    #[ORM\Column]
    #[Groups(['prioritaire:read'])]
    private ?int $niveau = null;

    #[ORM\OneToMany(mappedBy: 'niveau', targetEntity: Sav::class)]
    private Collection $savs;

    public function __construct()
    {
        $this->savs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNiveau(): ?int
    {
        return $this->niveau;
    }

    public function setNiveau(int $niveau): self
    {
        $this->niveau = $niveau;

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
            $sav->setNiveau($this);
        }

        return $this;
    }

    public function removeSav(Sav $sav): self
    {
        if ($this->savs->removeElement($sav)) {
            // set the owning side to null (unless already changed)
            if ($sav->getNiveau() === $this) {
                $sav->setNiveau(null);
            }
        }

        return $this;
    }
}
