<?php

namespace App\Entity;

use App\Repository\AgentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: AgentRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[UniqueEntity(fields: ['matricule'], message: 'Un agent avec ce matricule existe déjà.')]
class Agent
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;

    /**
     * @var Collection<int, Programme>
     */
    #[ORM\OneToMany(targetEntity: Programme::class, mappedBy: 'responsable_programme')]
    private Collection $programmes;

    /**
     * @var Collection<int, Action>
     */
    #[ORM\OneToMany(targetEntity: Action::class, mappedBy: 'responsable_action')]
    private Collection $actions;

    /**
     * @var Collection<int, Affectation>
     */
    #[ORM\OneToMany(targetEntity: Affectation::class, mappedBy: 'agent')]
    private Collection $affectations;

    #[ORM\Column(length: 255)]
    private ?string $cadre_statuaire = null;

    #[ORM\Column(length: 255)]
    private ?string $matricule = null;

    #[ORM\Column(length: 255)]
    private ?string $fonction = null;

    #[ORM\Column(length: 255)]
    private ?string $grade = null;

    #[ORM\Column(length: 255)]
    private ?string $echelon = null;

    #[ORM\Column(length: 255)]
    private ?string $numero_decision_contrat = null;

    #[ORM\Column(length: 255)]
    private ?string $decision_contrat = null;

    #[ORM\Column(length: 255)]
    private ?string $status = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?InfoPerso $identification = null;

    #[ORM\Column(length: 255)]
    private ?string $hierarchie = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTime $date_recrutement = null;

    #[ORM\Column(length: 255)]
    private ?string $banque = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $numeroCompte = null;

    public function __construct()
    {
        $this->programmes = new ArrayCollection();
        $this->actions = new ArrayCollection();
        $this->affectations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMatricule(): ?string
    {
        return $this->matricule;
    }

    public function setMatricule(string $matricule): static
    {
        $this->matricule = $matricule;

        return $this;
    }

    public function getFonction(): ?string
    {
        return $this->fonction;
    }

    public function setFonction(string $fonction): static
    {
        $this->fonction = $fonction;

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

    /**
     * @return Collection<int, Programme>
     */
    public function getProgrammes(): Collection
    {
        return $this->programmes;
    }

    public function addProgramme(Programme $programme): static
    {
        if (!$this->programmes->contains($programme)) {
            $this->programmes->add($programme);
            $programme->setResponsableProgramme($this);
        }

        return $this;
    }

    public function removeProgramme(Programme $programme): static
    {
        if ($this->programmes->removeElement($programme)) {
            // set the owning side to null (unless already changed)
            if ($programme->getResponsableProgramme() === $this) {
                $programme->setResponsableProgramme(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Action>
     */
    public function getActions(): Collection
    {
        return $this->actions;
    }

    public function addAction(Action $action): static
    {
        if (!$this->actions->contains($action)) {
            $this->actions->add($action);
            $action->setResponsableAction($this);
        }

        return $this;
    }

    public function removeAction(Action $action): static
    {
        if ($this->actions->removeElement($action)) {
            // set the owning side to null (unless already changed)
            if ($action->getResponsableAction() === $this) {
                $action->setResponsableAction(null);
            }
        }

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
            $affectation->setAgent($this);
        }

        return $this;
    }

    public function removeAffectation(Affectation $affectation): static
    {
        if ($this->affectations->removeElement($affectation)) {
            // set the owning side to null (unless already changed)
            if ($affectation->getAgent() === $this) {
                $affectation->setAgent(null);
            }
        }

        return $this;
    }

    public function getCadreStatuaire(): ?string
    {
        return $this->cadre_statuaire;
    }

    public function setCadreStatuaire(string $cadre_statuaire): static
    {
        $this->cadre_statuaire = $cadre_statuaire;

        return $this;
    }

    public function getGrade(): ?string
    {
        return $this->grade;
    }

    public function setGrade(string $grade): static
    {
        $this->grade = $grade;

        return $this;
    }

    public function getEchelon(): ?string
    {
        return $this->echelon;
    }

    public function setEchelon(string $echelon): static
    {
        $this->echelon = $echelon;

        return $this;
    }

    public function getNumeroDecisionContrat(): ?string
    {
        return $this->numero_decision_contrat;
    }

    public function setNumeroDecisionContrat(string $numero_decision_contrat): static
    {
        $this->numero_decision_contrat = $numero_decision_contrat;

        return $this;
    }

    public function getDecisionContrat(): ?string
    {
        return $this->decision_contrat;
    }

    public function setDecisionContrat(string $decision_contrat): static
    {
        $this->decision_contrat = $decision_contrat;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getIdentification(): ?InfoPerso
    {
        return $this->identification;
    }

    public function setIdentification(?InfoPerso $identification): static
    {
        $this->identification = $identification;

        return $this;
    }

    public function getHierarchie(): ?string
    {
        return $this->hierarchie;
    }

    public function setHierarchie(string $hierarchie): static
    {
        $this->hierarchie = $hierarchie;

        return $this;
    }

    public function getDateRecrutement(): ?\DateTime
    {
        return $this->date_recrutement;
    }

    public function setDateRecrutement(?\DateTime $date_recrutement): static
    {
        $this->date_recrutement = $date_recrutement;

        return $this;
    }

    public function getBanque(): ?string
    {
        return $this->banque;
    }

    public function setBanque(string $banque): static
    {
        $this->banque = $banque;

        return $this;
    }

    public function getNumeroCompte(): ?string
    {
        return $this->numeroCompte;
    }

    public function setNumeroCompte(?string $numeroCompte): static
    {
        $this->numeroCompte = $numeroCompte;

        return $this;
    }

}
