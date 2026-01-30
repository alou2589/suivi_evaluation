<?php

namespace App\Entity;

use App\Repository\AttributionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: AttributionRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[UniqueEntity(fields: ['affectaire', 'materiel'], message: 'Cette attribution existe déjà pour cette affectation et ce matériel.')]
class Attribution
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'attributions')]
    private ?Affectation $affectaire = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTime $date_attribution = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\ManyToOne(inversedBy: 'attributions')]
    private ?MatosInformatique $materiel = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAffectaire(): ?Affectation
    {
        return $this->affectaire;
    }

    public function setAffectaire(?Affectation $affectaire): static
    {
        $this->affectaire = $affectaire;

        return $this;
    }

    public function getDateAttribution(): ?\DateTime
    {
        return $this->date_attribution;
    }

    public function setDateAttribution(?\DateTime $date_attribution): static
    {
        $this->date_attribution = $date_attribution;

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

    #[ORM\PrePersist]
    public function setCreatedAtValue(): void
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->setUpdatedAtValue();
    }

    #[ORM\PreUpdate]
    public function setUpdatedAtValue(): void
    {
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function getMateriel(): ?MatosInformatique
    {
        return $this->materiel;
    }

    public function setMateriel(?MatosInformatique $materiel): static
    {
        $this->materiel = $materiel;

        return $this;
    }
}
