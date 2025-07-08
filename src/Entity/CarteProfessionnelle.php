<?php

namespace App\Entity;

use App\Repository\CarteProfessionnelleRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CarteProfessionnelleRepository::class)]
class CarteProfessionnelle
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'carteProfessionnelles')]
    private ?Affectation $identite = null;

    #[ORM\Column(length: 255)]
    private ?string $photo_agent = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $date_delivrance = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $date_expiration = null;

    #[ORM\Column(length: 255)]
    private ?string $status_impression = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdentite(): ?Affectation
    {
        return $this->identite;
    }

    public function setIdentite(?Affectation $identite): static
    {
        $this->identite = $identite;

        return $this;
    }

    public function getPhotoAgent(): ?string
    {
        return $this->photo_agent;
    }

    public function setPhotoAgent(string $photo_agent): static
    {
        $this->photo_agent = $photo_agent;

        return $this;
    }

    public function getDateDelivrance(): ?\DateTime
    {
        return $this->date_delivrance;
    }

    public function setDateDelivrance(\DateTime $date_delivrance): static
    {
        $this->date_delivrance = $date_delivrance;

        return $this;
    }

    public function getDateExpiration(): ?\DateTime
    {
        return $this->date_expiration;
    }

    public function setDateExpiration(\DateTime $date_expiration): static
    {
        $this->date_expiration = $date_expiration;

        return $this;
    }

    public function getStatusImpression(): ?string
    {
        return $this->status_impression;
    }

    public function setStatusImpression(string $status_impression): static
    {
        $this->status_impression = $status_impression;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
