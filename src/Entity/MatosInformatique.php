<?php

namespace App\Entity;

use App\Repository\MatosInformatiqueRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MatosInformatiqueRepository::class)]
class MatosInformatique
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $type_matos = null;

    #[ORM\Column(length: 255)]
    private ?string $marque_matos = null;

    #[ORM\Column(length: 255)]
    private ?string $modele_matos = null;

    #[ORM\Column(length: 255)]
    private ?string $sn_matos = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $date_reception = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $specification = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTypeMatos(): ?string
    {
        return $this->type_matos;
    }

    public function setTypeMatos(string $type_matos): static
    {
        $this->type_matos = $type_matos;

        return $this;
    }

    public function getMarqueMatos(): ?string
    {
        return $this->marque_matos;
    }

    public function setMarqueMatos(string $marque_matos): static
    {
        $this->marque_matos = $marque_matos;

        return $this;
    }

    public function getModeleMatos(): ?string
    {
        return $this->modele_matos;
    }

    public function setModeleMatos(string $modele_matos): static
    {
        $this->modele_matos = $modele_matos;

        return $this;
    }

    public function getSnMatos(): ?string
    {
        return $this->sn_matos;
    }

    public function setSnMatos(string $sn_matos): static
    {
        $this->sn_matos = $sn_matos;

        return $this;
    }

    public function getDateReception(): ?\DateTime
    {
        return $this->date_reception;
    }

    public function setDateReception(\DateTime $date_reception): static
    {
        $this->date_reception = $date_reception;

        return $this;
    }

    public function getSpecification(): ?string
    {
        return $this->specification;
    }

    public function setSpecification(?string $specification): static
    {
        $this->specification = $specification;

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
}
