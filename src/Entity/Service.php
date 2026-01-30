<?php

namespace App\Entity;

use App\Repository\ServiceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: ServiceRepository::class)]
#[UniqueEntity(fields: ['nom_service'], message: 'Un service avec ce nom existe déjà.')]
#[HasLifecycleCallbacks]

class Service
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom_service = null;

    #[ORM\ManyToOne(inversedBy: 'services')]
    private ?Direction $structure_rattachee = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\Column(length: 255)]
    private ?string $type_service = null;

    /**
     * @var Collection<int, Affectation>
     */
    #[ORM\OneToMany(targetEntity: Affectation::class, mappedBy: 'service')]
    private Collection $affectations;

    /**
     * @var Collection<int, SousStructure>
     */
    #[ORM\OneToMany(targetEntity: SousStructure::class, mappedBy: 'service_rattache')]
    private Collection $sousStructures;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'services')]
    private ?self $service_parent = null;

    /**
     * @var Collection<int, self>
     */
    #[ORM\OneToMany(targetEntity: self::class, mappedBy: 'service_parent')]
    private Collection $services;


    public function __construct()
    {
        $this->affectations = new ArrayCollection();
        $this->sousStructures = new ArrayCollection();
        $this->services = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomService(): ?string
    {
        return $this->nom_service;
    }

    public function setNomService(string $nom_service): static
    {
        $this->nom_service = $nom_service;

        return $this;
    }

    public function getStructureRattachee(): ?Direction
    {
        return $this->structure_rattachee;
    }

    public function setStructureRattachee(?Direction $structure_rattachee): static
    {
        $this->structure_rattachee = $structure_rattachee;

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

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }


    public function getTypeService(): ?string
    {
        return $this->type_service;
    }

    public function setTypeService(string $type_service): static
    {
        $this->type_service = $type_service;

        return $this;
    }

    /**
     * @return Collection<int, Affectation>
     */
    public function getAffectations(): Collection
    {
        return $this->affectations;
    }

    public function addAffectation(Affectation $affectation): static
    {
        if (!$this->affectations->contains($affectation)) {
            $this->affectations->add($affectation);
            $affectation->setService($this);
        }

        return $this;
    }

    public function removeAffectation(Affectation $affectation): static
    {
        if ($this->affectations->removeElement($affectation)) {
            // set the owning side to null (unless already changed)
            if ($affectation->getService() === $this) {
                $affectation->setService(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, SousStructure>
     */
    public function getSousStructures(): Collection
    {
        return $this->sousStructures;
    }

    public function addSousStructure(SousStructure $sousStructure): static
    {
        if (!$this->sousStructures->contains($sousStructure)) {
            $this->sousStructures->add($sousStructure);
            $sousStructure->setServiceRattache($this);
        }

        return $this;
    }

    public function removeSousStructure(SousStructure $sousStructure): static
    {
        if ($this->sousStructures->removeElement($sousStructure)) {
            // set the owning side to null (unless already changed)
            if ($sousStructure->getServiceRattache() === $this) {
                $sousStructure->setServiceRattache(null);
            }
        }

        return $this;
    }

    public function getServiceParent(): ?self
    {
        return $this->service_parent;
    }

    public function setServiceParent(?self $service_parent): static
    {
        $this->service_parent = $service_parent;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getServices(): Collection
    {
        return $this->services;
    }

    public function addService(self $service): static
    {
        if (!$this->services->contains($service)) {
            $this->services->add($service);
            $service->setServiceParent($this);
        }

        return $this;
    }

    public function removeService(self $service): static
    {
        if ($this->services->removeElement($service)) {
            // set the owning side to null (unless already changed)
            if ($service->getServiceParent() === $this) {
                $service->setServiceParent(null);
            }
        }

        return $this;
    }

}
