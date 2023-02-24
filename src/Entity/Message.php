<?php

namespace App\Entity;

use App\Repository\MessageRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Serializer\Annotation\Groups;


#[ORM\Entity(repositoryClass: MessageRepository::class)]
class Message
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['message:read'])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'messages')]
    #[Groups(['message:read'])]
    private ?Sav $sav = null;

    #[ORM\Column(length: 255)]
    #[Groups(['message:read'])]
    private ?string $message = null;

    #[ORM\Column]
    #[Groups(['message:read'])]
    private ?bool $type = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(['message:read'])]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column]
    #[Groups(['message:read'])]
    private ?bool $readEtat = null;

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

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function isType(): ?bool
    {
        return $this->type;
    }

    public function setType(bool $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function isReadEtat(): ?bool
    {
        return $this->readEtat;
    }

    public function setReadEtat(bool $readEtat): self
    {
        $this->readEtat = $readEtat;

        return $this;
    }
}
