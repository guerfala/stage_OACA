<?php

namespace App\Entity;

use App\Repository\InterventionsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: InterventionsRepository::class)]
class Interventions
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $au_service = null;

    #[ORM\Column(length: 255)]
    private ?string $service_demandeur = null;

    #[ORM\Column(length: 255)]
    private ?string $code_imp = null;

    #[ORM\Column(length: 255)]
    private ?string $reference = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(length: 255)]
    private ?string $batiment = null;

    #[ORM\Column(length: 255)]
    private ?string $local = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $piece_jointe = null;

    #[ORM\Column(length: 255)]
    private ?string $deg_urgence = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAuService(): ?string
    {
        return $this->au_service;
    }

    public function setAuService(string $au_service): static
    {
        $this->au_service = $au_service;

        return $this;
    }

    public function getServiceDemandeur(): ?string
    {
        return $this->service_demandeur;
    }

    public function setServiceDemandeur(string $service_demandeur): static
    {
        $this->service_demandeur = $service_demandeur;

        return $this;
    }

    public function getCodeImp(): ?string
    {
        return $this->code_imp;
    }

    public function setCodeImp(string $code_imp): static
    {
        $this->code_imp = $code_imp;

        return $this;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(string $reference): static
    {
        $this->reference = $reference;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getBatiment(): ?string
    {
        return $this->batiment;
    }

    public function setBatiment(string $batiment): static
    {
        $this->batiment = $batiment;

        return $this;
    }

    public function getLocal(): ?string
    {
        return $this->local;
    }

    public function setLocal(string $local): static
    {
        $this->local = $local;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getPieceJointe(): ?string
    {
        return $this->piece_jointe;
    }

    public function setPieceJointe(?string $piece_jointe): static
    {
        $this->piece_jointe = $piece_jointe;

        return $this;
    }

    public function getDegUrgence(): ?string
    {
        return $this->deg_urgence;
    }

    public function setDegUrgence(string $deg_urgence): static
    {
        $this->deg_urgence = $deg_urgence;

        return $this;
    }
}
