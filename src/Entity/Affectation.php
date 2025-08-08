<?php

namespace App\Entity;

use App\Repository\AffectationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: AffectationRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[UniqueEntity(fields: ['agent', 'poste', 'service', 'date_debut'], message: 'Une affectation avec ces paramètres existe déjà.')]
class Affectation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'affectations')]
    private ?Agent $agent = null;

    #[ORM\ManyToOne(inversedBy: 'affectations')]
    private ?Poste $poste = null;

    #[ORM\ManyToOne(inversedBy: 'affectations')]
    private ?Service $service = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $date_debut = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $date_fin = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\Column(length: 255)]
    private ?string $statut_affectation = null;

    /**
     * @var Collection<int, CarteProfessionnelle>
     */
    #[ORM\OneToMany(targetEntity: CarteProfessionnelle::class, mappedBy: 'identite')]
    private Collection $carteProfessionnelles;

    /**
     * @var Collection<int, Attribution>
     */
    #[ORM\OneToMany(targetEntity: Attribution::class, mappedBy: 'affectaire')]
    private Collection $attributions;

    /**
     * @var Collection<int, Technicien>
     */
    #[ORM\OneToMany(targetEntity: Technicien::class, mappedBy: 'info_technicien')]
    private Collection $techniciens;

    public function __construct()
    {
        $this->carteProfessionnelles = new ArrayCollection();
        $this->attributions = new ArrayCollection();
        $this->techniciens = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAgent(): ?Agent
    {
        return $this->agent;
    }

    public function setAgent(?Agent $agent): static
    {
        $this->agent = $agent;

        return $this;
    }

    public function getPoste(): ?Poste
    {
        return $this->poste;
    }

    public function setPoste(?Poste $poste): static
    {
        $this->poste = $poste;

        return $this;
    }

    public function getService(): ?Service
    {
        return $this->service;
    }

    public function setService(?Service $service): static
    {
        $this->service = $service;

        return $this;
    }

    public function getDateDebut(): ?\DateTimeImmutable
    {
        return $this->date_debut;
    }

    public function setDateDebut(\DateTimeImmutable $date_debut): static
    {
        $this->date_debut = $date_debut;

        return $this;
    }

    public function getDateFin(): ?\DateTimeImmutable
    {
        return $this->date_fin;
    }

    public function setDateFin(?\DateTimeImmutable $date_fin): static
    {
        $this->date_fin = $date_fin;

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

    public function getStatutAffectation(): ?string
    {
        return $this->statut_affectation;
    }

    public function setStatutAffectation(string $statut_affectation): static
    {
        $this->statut_affectation = $statut_affectation;

        return $this;
    }

    /**
     * @return Collection<int, CarteProfessionnelle>
     */
    public function getCarteProfessionnelles(): Collection
    {
        return $this->carteProfessionnelles;
    }

    public function addCarteProfessionnelle(CarteProfessionnelle $carteProfessionnelle): static
    {
        if (!$this->carteProfessionnelles->contains($carteProfessionnelle)) {
            $this->carteProfessionnelles->add($carteProfessionnelle);
            $carteProfessionnelle->setIdentite($this);
        }

        return $this;
    }

    public function removeCarteProfessionnelle(CarteProfessionnelle $carteProfessionnelle): static
    {
        if ($this->carteProfessionnelles->removeElement($carteProfessionnelle)) {
            // set the owning side to null (unless already changed)
            if ($carteProfessionnelle->getIdentite() === $this) {
                $carteProfessionnelle->setIdentite(null);
            }
        }

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
            $attribution->setAffectaire($this);
        }

        return $this;
    }

    public function removeAttribution(Attribution $attribution): static
    {
        if ($this->attributions->removeElement($attribution)) {
            // set the owning side to null (unless already changed)
            if ($attribution->getAffectaire() === $this) {
                $attribution->setAffectaire(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Technicien>
     */
    public function getTechniciens(): Collection
    {
        return $this->techniciens;
    }

    public function addTechnicien(Technicien $technicien): static
    {
        if (!$this->techniciens->contains($technicien)) {
            $this->techniciens->add($technicien);
            $technicien->setInfoTechnicien($this);
        }

        return $this;
    }

    public function removeTechnicien(Technicien $technicien): static
    {
        if ($this->techniciens->removeElement($technicien)) {
            // set the owning side to null (unless already changed)
            if ($technicien->getInfoTechnicien() === $this) {
                $technicien->setInfoTechnicien(null);
            }
        }

        return $this;
    }

}
