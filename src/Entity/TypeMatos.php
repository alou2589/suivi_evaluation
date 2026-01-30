<?php

namespace App\Entity;

use App\Repository\TypeMatosRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TypeMatosRepository::class)]
#[ORM\HasLifecycleCallbacks]

class TypeMatos
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom_type = null;

    #[ORM\Column(length: 255)]
    private ?string $description_type = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;

    /**
     * @var Collection<int, MatosInformatique>
     */
    #[ORM\OneToMany(targetEntity: MatosInformatique::class, mappedBy: 'type_materiel')]
    private Collection $matosInformatiques;

    public function __construct()
    {
        $this->matosInformatiques = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomType(): ?string
    {
        return $this->nom_type;
    }

    public function setNomType(string $nom_type): static
    {
        $this->nom_type = $nom_type;

        return $this;
    }

    public function getDescriptionType(): ?string
    {
        return $this->description_type;
    }

    public function setDescriptionType(string $description_type): static
    {
        $this->description_type = $description_type;

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
     * @return Collection<int, MatosInformatique>
     */
    public function getMatosInformatiques(): Collection
    {
        return $this->matosInformatiques;
    }

    public function addMatosInformatique(MatosInformatique $matosInformatique): static
    {
        if (!$this->matosInformatiques->contains($matosInformatique)) {
            $this->matosInformatiques->add($matosInformatique);
            $matosInformatique->setTypeMateriel($this);
        }

        return $this;
    }

    public function removeMatosInformatique(MatosInformatique $matosInformatique): static
    {
        if ($this->matosInformatiques->removeElement($matosInformatique)) {
            // set the owning side to null (unless already changed)
            if ($matosInformatique->getTypeMateriel() === $this) {
                $matosInformatique->setTypeMateriel(null);
            }
        }

        return $this;
    }
}
