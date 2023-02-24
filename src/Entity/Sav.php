<?php

namespace App\Entity;

use App\Repository\SavRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: SavRepository::class)]
class Sav
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['sav:read'])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'savs')]
    #[Groups(['sav:read'])]
    private ?Magasin $magasin = null;

    #[ORM\ManyToOne(inversedBy: 'savs')]
    #[Groups(['sav:read'])]
    private ?Client $client = null;

    #[ORM\Column(length: 255)]
    #[Groups(['sav:read'])]
    private ?string $refSav = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Groups(['sav:read'])]
    private ?\DateTimeInterface $dateAppel = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Groups(['sav:read'])]
    private ?\DateTimeInterface $dateIntervantion = null;

    #[ORM\Column]
    #[Groups(['sav:read'])]
    private ?int $nbEmplacement = null;

    #[ORM\OneToMany(mappedBy: 'sav', targetEntity: Message::class)]
    #[Groups(['messageInSav:read'])]
    private Collection $messages;

    #[ORM\Column]
    #[Groups(['sav:read'])]
    private ?int $etat = null;

    #[ORM\OneToMany(mappedBy: 'sav', targetEntity: Probleme::class, cascade: ['persist'])]
    #[Groups(['problemeInsav:read'])]
    private Collection $problemes;

    #[ORM\ManyToOne(inversedBy: 'savs')]
    #[Groups(['sav:read'])]
    private ?Prioritaire $niveau = null;

    #[ORM\Column]
    #[Groups(['sav:read'])]
    private ?bool $intervention = null;

    #[ORM\Column]
    #[Groups(['sav:read'])]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\ManyToOne(inversedBy: 'savs')]
    private ?User $user_id = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['sav:read'])]
    private ?int $admin_creer = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $solution = null;

    public function __construct()
    {
        $this->messages = new ArrayCollection();
        $this->problemes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): self
    {
        $this->client = $client;

        return $this;
    }

    public function getRefSav(): ?string
    {
        return $this->refSav;
    }

    public function setRefSav(string $refSav): self
    {
        $this->refSav = $refSav;

        return $this;
    }
    public function getDateAppel(): ?\DateTimeInterface
    {
        return $this->dateAppel;
    }

    public function setDateAppel(\DateTimeInterface $dateAppel): self
    {
        $this->dateAppel = $dateAppel;

        return $this;
    }

    public function getDateIntervantion(): ?\DateTimeInterface
    {
        return $this->dateIntervantion;
    }

    public function setDateIntervantion(\DateTimeInterface $dateIntervantion): self
    {
        $this->dateIntervantion = $dateIntervantion;

        return $this;
    }

    public function getNbEmplacement(): ?int
    {
        return $this->nbEmplacement;
    }

    public function setNbEmplacement(int $nbEmplacement): self
    {
        $this->nbEmplacement = $nbEmplacement;

        return $this;
    }

    /**
     * @return Collection<int, Message>
     */
    public function getMessages(): Collection
    {
        return $this->messages;
    }

    public function addMessage(Message $message): self
    {
        if (!$this->messages->contains($message)) {
            $this->messages->add($message);
            $message->setSav($this);
        }

        return $this;
    }

    public function removeMessage(Message $message): self
    {
        if ($this->messages->removeElement($message)) {
            // set the owning side to null (unless already changed)
            if ($message->getSav() === $this) {
                $message->setSav(null);
            }
        }

        return $this;
    }

    public function getEtat(): ?int
    {
        return $this->etat;
    }

    public function setEtat(int $etat): self
    {
        $this->etat = $etat;

        return $this;
    }

    /**
     * @return Collection<int, Probleme>
     */
    public function getProblemes(): Collection
    {
        return $this->problemes;
    }

    public function addProbleme(Probleme $probleme): self
    {
        if (!$this->problemes->contains($probleme)) {
            $this->problemes->add($probleme);
            $probleme->setSav($this);
        }

        return $this;
    }

    public function removeProbleme(Probleme $probleme): self
    {
        if ($this->problemes->removeElement($probleme)) {
            // set the owning side to null (unless already changed)
            if ($probleme->getSav() === $this) {
                $probleme->setSav(null);
            }
        }

        return $this;
    }

    public function getNiveau(): ?Prioritaire
    {
        return $this->niveau;
    }

    public function setNiveau(?Prioritaire $niveau): self
    {
        $this->niveau = $niveau;

        return $this;
    }

    public function isIntervention(): ?bool
    {
        return $this->intervention;
    }

    public function setIntervention(bool $intervention): self
    {
        $this->intervention = $intervention;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUserId(): ?User
    {
        return $this->user_id;
    }

    public function setUserId(?User $user_id): self
    {
        $this->user_id = $user_id;

        return $this;
    }

    public function getAdminCreer(): ?int
    {
        return $this->admin_creer;
    }

    public function setAdminCreer(?int $admin_creer): self
    {
        $this->admin_creer = $admin_creer;

        return $this;
    }
    
    public function getSolution(): ?string
    {
        return $this->solution;
    }

    public function setSolution(?string $solution): self
    {
        $this->solution = $solution;

        return $this;
    }
}
