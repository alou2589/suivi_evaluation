<?php

namespace App\Entity;

use App\Repository\DocumentAdministratifRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;


#[ORM\Entity(repositoryClass: DocumentAdministratifRepository::class)]
#[HasLifecycleCallbacks]
#[UniqueEntity(fields: ['nom_doc'], message: 'Un document avec ce nom existe déjà.')]
class DocumentAdministratif
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $type_doc = null;

    #[ORM\Column(length: 255)]
    private ?string $nom_doc = null;

    #[ORM\Column(length: 255)]
    private ?string $document = null;

    #[ORM\ManyToOne(inversedBy: 'documentAdministratifs')]
    private ?Agent $agent = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTypeDoc(): ?string
    {
        return $this->type_doc;
    }

    public function setTypeDoc(string $type_doc): static
    {
        $this->type_doc = $type_doc;

        return $this;
    }

    public function getNomDoc(): ?string
    {
        return $this->nom_doc;
    }

    public function setNomDoc(string $nom_doc): static
    {
        $this->nom_doc = $nom_doc;

        return $this;
    }

    public function getDocument(): ?string
    {
        return $this->document;
    }

    public function setDocument(string $document): static
    {
        $this->document = $document;

        return $this;
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
}
