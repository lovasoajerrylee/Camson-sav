<?php

namespace App\Entity;

use App\Repository\ClientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;

use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ClientRepository::class)]
class Client
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['client:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['client:read'])]
    private ?string $nomClient = null;

    #[ORM\Column(length: 255)]
    #[Groups(['client:read'])]
    private ?string $prenomClient = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['client:read'])]
    private ?string $telFixe = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['client:read'])]
    private ?string $telPortable1 = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['client:read'])]
    private ?string $telPortable2 = null;

    #[ORM\Column(length: 255)]
    #[Groups(['client:read'])]
    private ?string $refClient = null;

    #[ORM\Column]
    #[Groups(['client:read'])]
    private ?bool $etat = null;

    #[ORM\OneToMany(mappedBy: 'client', targetEntity: Sav::class)]
    private Collection $savs;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    #[Groups(['client:read'])]
    private ?\DateTimeInterface $datePaiment = null;

    #[ORM\Column(length: 45, nullable: true)]
    #[Groups(['client:read'])]
    private ?string $nomGerant = null;

    #[ORM\Column(length: 55, nullable: true)]
    #[Groups(['client:read'])]
    private ?string $email = null;

    public function __construct()
    {
        $this->savs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomClient(): ?string
    {
        return $this->nomClient;
    }

    public function setNomClient(string $nomClient): self
    {
        $this->nomClient = $nomClient;

        return $this;
    }

    public function getPrenomClient(): ?string
    {
        return $this->prenomClient;
    }

    public function setPrenomClient(string $prenomClient): self
    {
        $this->prenomClient = $prenomClient;

        return $this;
    }

    public function getTelFixe(): ?string
    {
        return $this->telFixe;
    }

    public function setTelFixe(?string $telFixe): self
    {
        $this->telFixe = $telFixe;

        return $this;
    }

    public function getTelPortable1(): ?string
    {
        return $this->telPortable1;
    }

    public function setTelPortable1(?string $telPortable1): self
    {
        $this->telPortable1 = $telPortable1;

        return $this;
    }

    public function getTelPortable2(): ?string
    {
        return $this->telPortable2;
    }

    public function setTelPortable2(?string $telPortable2): self
    {
        $this->telPortable2 = $telPortable2;

        return $this;
    }

    public function getRefClient(): ?string
    {
        return $this->refClient;
    }

    public function setRefClient(string $refClient): self
    {
        $this->refClient = $refClient;

        return $this;
    }

    public function isEtat(): ?bool
    {
        return $this->etat;
    }

    public function setEtat(bool $etat): self
    {
        $this->etat = $etat;

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
            $sav->setClient($this);
        }

        return $this;
    }

    public function removeSav(Sav $sav): self
    {
        if ($this->savs->removeElement($sav)) {
            // set the owning side to null (unless already changed)
            if ($sav->getClient() === $this) {
                $sav->setClient(null);
            }
        }

        return $this;
    }

    public function getDatePaiment(): ?\DateTimeInterface
    {
        return $this->datePaiment;
    }

    public function setDatePaiment(?\DateTimeInterface $datePaiment): self
    {
        $this->datePaiment = $datePaiment;

        return $this;
    }

    public function getNomGerant(): ?string
    {
        return $this->nomGerant;
    }

    public function setNomGerant(?string $nomGerant): self
    {
        $this->nomGerant = $nomGerant;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }
}
