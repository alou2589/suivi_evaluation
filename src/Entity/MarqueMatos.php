<?php

namespace App\Entity;

use App\Repository\MarqueMatosRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


#[ORM\HasLifecycleCallbacks]
#[UniqueEntity(fields: ['nom_marque'], message: 'Une action avec ce code existe déjà.')]
#[ORM\Entity(repositoryClass: MarqueMatosRepository::class)]
class MarqueMatos
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom_marque = null;

    #[ORM\Column(length: 255)]
    private ?string $description_marque = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;

    /**
     * @var Collection<int, MatosInformatique>
     */
    #[ORM\OneToMany(targetEntity: MatosInformatique::class, mappedBy: 'marque_matos')]
    private Collection $matosInformatiques;

    public function __construct()
    {
        $this->matosInformatiques = new ArrayCollection();
    }



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomMarque(): ?string
    {
        return $this->nom_marque;
    }

    public function setNomMarque(string $nom_marque): static
    {
        $this->nom_marque = $nom_marque;

        return $this;
    }

    public function getDescriptionMarque(): ?string
    {
        return $this->description_marque;
    }

    public function setDescriptionMarque(string $description_marque): static
    {
        $this->description_marque = $description_marque;

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
            $matosInformatique->setMarqueMatos($this);
        }

        return $this;
    }

    public function removeMatosInformatique(MatosInformatique $matosInformatique): static
    {
        if ($this->matosInformatiques->removeElement($matosInformatique)) {
            // set the owning side to null (unless already changed)
            if ($matosInformatique->getMarqueMatos() === $this) {
                $matosInformatique->setMarqueMatos(null);
            }
        }

        return $this;
    }
}
