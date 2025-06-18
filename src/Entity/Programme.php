<?php

namespace App\Entity;

use App\Repository\ProgrammeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: ProgrammeRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[UniqueEntity(fields: ['code_programme'], message: 'Un programme avec ce code existe déjà.')]
#[UniqueEntity(fields: ['nom_programme'], message: 'Un programme avec ce nom existe déjà.')]
#[ORM\Table(name: 'programme')]
class Programme
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $code_programme = null;

    #[ORM\Column(length: 255)]
    private ?string $nom_programme = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\Column(length: 255)]
    private ?string $annee_programme = null;

    #[ORM\ManyToOne(inversedBy: 'programmes')]
    private ?Agent $responsable_programme = null;

    #[ORM\Column(length: 255)]
    private ?string $cout_programme = null;

    /**
     * @var Collection<int, Action>
     */
    #[ORM\OneToMany(targetEntity: Action::class, mappedBy: 'programme')]
    private Collection $actions;

    public function __construct()
    {
        $this->actions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCodeProgramme(): ?string
    {
        return $this->code_programme;
    }

    public function setCodeProgramme(string $code_programme): static
    {
        $this->code_programme = $code_programme;

        return $this;
    }

    public function getNomProgramme(): ?string
    {
        return $this->nom_programme;
    }

    public function setNomProgramme(string $nom_programme): static
    {
        $this->nom_programme = $nom_programme;

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

    public function getAnneeProgramme(): ?string
    {
        return $this->annee_programme;
    }

    public function setAnneeProgramme(string $annee_programme): static
    {
        $this->annee_programme = $annee_programme;

        return $this;
    }

    public function getResponsableProgramme(): ?Agent
    {
        return $this->responsable_programme;
    }

    public function setResponsableProgramme(?Agent $responsable_programme): static
    {
        $this->responsable_programme = $responsable_programme;

        return $this;
    }

    public function getCoutProgramme(): ?string
    {
        return $this->cout_programme;
    }

    public function setCoutProgramme(string $cout_programme): static
    {
        $this->cout_programme = $cout_programme;

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
            $action->setProgramme($this);
        }

        return $this;
    }

    public function removeAction(Action $action): static
    {
        if ($this->actions->removeElement($action)) {
            // set the owning side to null (unless already changed)
            if ($action->getProgramme() === $this) {
                $action->setProgramme(null);
            }
        }

        return $this;
    }
}
