<?php

namespace App\Entity;

use App\Repository\ActionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: ActionRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[ORM\Table(name: 'action')]
#[UniqueEntity(fields: ['code_action'], message: 'Une action avec ce code existe déjà.')]
#[UniqueEntity(fields: ['nom_action'], message: 'Une action avec ce nom existe déjà.')]
class Action
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom_action = null;

    #[ORM\ManyToOne(inversedBy: 'actions')]
    private ?Programme $programme = null;

    #[ORM\Column(length: 255)]
    private ?string $code_action = null;


    /**
     * @var Collection<int, self>
     */
    #[ORM\OneToMany(targetEntity: self::class, mappedBy: 'responsable_action')]
    private Collection $actions;

    #[ORM\Column(length: 255)]
    private ?string $cout_action = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\ManyToOne(inversedBy: 'actions')]
    private ?Agent $responsable_action = null;

    public function __construct()
    {
        $this->actions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomAction(): ?string
    {
        return $this->nom_action;
    }

    public function setNomAction(string $nom_action): static
    {
        $this->nom_action = $nom_action;

        return $this;
    }

    public function getProgramme(): ?Programme
    {
        return $this->programme;
    }

    public function setProgramme(?Programme $programme): static
    {
        $this->programme = $programme;

        return $this;
    }

    public function getCodeAction(): ?string
    {
        return $this->code_action;
    }

    public function setCodeAction(string $code_action): static
    {
        $this->code_action = $code_action;

        return $this;
    }


    public function getCoutAction(): ?string
    {
        return $this->cout_action;
    }

    public function setCoutAction(string $cout_action): static
    {
        $this->cout_action = $cout_action;

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

    public function getResponsableAction(): ?Agent
    {
        return $this->responsable_action;
    }

    public function setResponsableAction(?Agent $responsable_action): static
    {
        $this->responsable_action = $responsable_action;

        return $this;
    }
}
