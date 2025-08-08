<?php

namespace App\Entity;

use App\Repository\MaintenanceRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: MaintenanceRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[UniqueEntity(fields: ['materiel', 'date_maintenance'], message: 'Une maintenance pour ce matériel à cette date existe déjà.')]
class Maintenance
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'maintenances')]
    private ?MatosInformatique $materiel = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $date_maintenance = null;

    #[ORM\Column(length: 255)]
    private ?string $status_matos = null;

    #[ORM\Column(length: 255)]
    private ?string $fiche_maintenance = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\Column(length: 255)]
    private ?string $prestataire = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDateMaintenance(): ?\DateTime
    {
        return $this->date_maintenance;
    }

    public function setDateMaintenance(\DateTime $date_maintenance): static
    {
        $this->date_maintenance = $date_maintenance;

        return $this;
    }

    public function getStatusMatos(): ?string
    {
        return $this->status_matos;
    }

    public function setStatusMatos(string $status_matos): static
    {
        $this->status_matos = $status_matos;

        return $this;
    }

    public function getFicheMaintenance(): ?string
    {
        return $this->fiche_maintenance;
    }

    public function setFicheMaintenance(string $fiche_maintenance): static
    {
        $this->fiche_maintenance = $fiche_maintenance;

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

    public function getPrestataire(): ?string
    {
        return $this->prestataire;
    }

    public function setPrestataire(string $prestataire): static
    {
        $this->prestataire = $prestataire;

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
}
