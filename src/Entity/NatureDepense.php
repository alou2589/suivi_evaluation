<?php

namespace App\Entity;

use App\Repository\NatureDepenseRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: NatureDepenseRepository::class)]
class NatureDepense
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom_nature = null;

    #[ORM\ManyToOne(inversedBy: 'natureDepenses')]
    private ?Action $action = null;

    #[ORM\Column(length: 255)]
    private ?string $budget_cp = null;

    #[ORM\Column(length: 255)]
    private ?string $budget_ae = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomNature(): ?string
    {
        return $this->nom_nature;
    }

    public function setNomNature(string $nom_nature): static
    {
        $this->nom_nature = $nom_nature;

        return $this;
    }

    public function getAction(): ?Action
    {
        return $this->action;
    }

    public function setAction(?Action $action): static
    {
        $this->action = $action;

        return $this;
    }

    public function getBudgetCp(): ?string
    {
        return $this->budget_cp;
    }

    public function setBudgetCp(string $budget_cp): static
    {
        $this->budget_cp = $budget_cp;

        return $this;
    }

    public function getBudgetAe(): ?string
    {
        return $this->budget_ae;
    }

    public function setBudgetAe(string $budget_ae): static
    {
        $this->budget_ae = $budget_ae;

        return $this;
    }
}
