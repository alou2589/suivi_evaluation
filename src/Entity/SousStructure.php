<?php

namespace App\Entity;

use App\Repository\SousStructureRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;


#[ORM\Entity(repositoryClass: SousStructureRepository::class)]
#[HasLifecycleCallbacks]

class SousStructure
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom_sous_structure = null;

    #[ORM\ManyToOne(inversedBy: 'sousStructures')]
    private ?Service $service_rattache = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomSousStructure(): ?string
    {
        return $this->nom_sous_structure;
    }

    public function setNomSousStructure(string $nom_sous_structure): static
    {
        $this->nom_sous_structure = $nom_sous_structure;

        return $this;
    }

    public function getServiceRattache(): ?Service
    {
        return $this->service_rattache;
    }

    public function setServiceRattache(?Service $service_rattache): static
    {
        $this->service_rattache = $service_rattache;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

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
