<?php

namespace App\Entity;

use App\Repository\VisiteRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;

#[ORM\Entity(repositoryClass: VisiteRepository::class)]
#[HasLifecycleCallbacks]
class Visite
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'visites')]
    private ?User $user = null;

    #[ORM\Column]
    private ?\DateTime $date_action = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $action = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $pageVisitee = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $ipVisiteur = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $navigateur = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getDateAction(): ?\DateTime
    {
        return $this->date_action;
    }

    public function setDateAction(\DateTime $date_action): static
    {
        $this->date_action = $date_action;

        return $this;
    }

    public function getAction(): ?string
    {
        return $this->action;
    }

    public function setAction(?string $action): static
    {
        $this->action = $action;

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

    public function getPageVisitee(): ?string
    {
        return $this->pageVisitee;
    }

    public function setPageVisitee(?string $pageVisitee): static
    {
        $this->pageVisitee = $pageVisitee;

        return $this;
    }

    public function getIpVisiteur(): ?string
    {
        return $this->ipVisiteur;
    }

    public function setIpVisiteur(?string $ipVisiteur): static
    {
        $this->ipVisiteur = $ipVisiteur;

        return $this;
    }

    public function getNavigateur(): ?string
    {
        return $this->navigateur;
    }

    public function setNavigateur(?string $navigateur): static
    {
        $this->navigateur = $navigateur;

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
