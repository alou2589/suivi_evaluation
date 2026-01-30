<?php

namespace App\Entity;

use App\Repository\MatosInformatiqueRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: MatosInformatiqueRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[UniqueEntity(fields: ['type_matos', 'modele_matos', 'sn_matos'], message: 'Ce matériel informatique existe déjà.')]

class MatosInformatique
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $modele_matos = null;

    #[ORM\Column(length: 255)]
    private ?string $sn_matos = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTime $date_reception = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $specification = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;


    /**
     * @var Collection<int, Maintenance>
     */
    #[ORM\OneToMany(targetEntity: Maintenance::class, mappedBy: 'materiel')]
    private Collection $maintenances;

    #[ORM\ManyToOne(inversedBy: 'matosInformatiques')]
    private ?MarqueMatos $marque_matos = null;

    /**
     * @var Collection<int, Attribution>
     */
    #[ORM\OneToMany(targetEntity: Attribution::class, mappedBy: 'materiel')]
    private Collection $attributions;

    #[ORM\ManyToOne(inversedBy: 'matosInformatiques')]
    private ?TypeMatos $type_materiel = null;


    public function __construct()
    {
        $this->maintenances = new ArrayCollection();
        $this->attributions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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


    /**
     * @return Collection<int, Maintenance>
     */
    public function getMaintenances(): Collection
    {
        return $this->maintenances;
    }

    public function addMaintenance(Maintenance $maintenance): static
    {
        if (!$this->maintenances->contains($maintenance)) {
            $this->maintenances->add($maintenance);
            $maintenance->setMateriel($this);
        }

        return $this;
    }

    public function removeMaintenance(Maintenance $maintenance): static
    {
        if ($this->maintenances->removeElement($maintenance)) {
            // set the owning side to null (unless already changed)
            if ($maintenance->getMateriel() === $this) {
                $maintenance->setMateriel(null);
            }
        }

        return $this;
    }

    public function getMarqueMatos(): ?MarqueMatos
    {
        return $this->marque_matos;
    }

    public function setMarqueMatos(?MarqueMatos $marque_matos): static
    {
        $this->marque_matos = $marque_matos;

        return $this;
    }

    /**
     * @return Collection<int, Attribution>
     */
    public function getAttributions(): Collection
    {
        return $this->attributions;
    }

    public function addAttribution(Attribution $attribution): static
    {
        if (!$this->attributions->contains($attribution)) {
            $this->attributions->add($attribution);
            $attribution->setMateriel($this);
        }

        return $this;
    }

    public function removeAttribution(Attribution $attribution): static
    {
        if ($this->attributions->removeElement($attribution)) {
            // set the owning side to null (unless already changed)
            if ($attribution->getMateriel() === $this) {
                $attribution->setMateriel(null);
            }
        }

        return $this;
    }

    public function getTypeMateriel(): ?TypeMatos
    {
        return $this->type_materiel;
    }

    public function setTypeMateriel(?TypeMatos $type_materiel): static
    {
        $this->type_materiel = $type_materiel;

        return $this;
    }


}
