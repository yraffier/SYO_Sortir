<?php

namespace App\Entity;

use App\Repository\SearchDataRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

class SearchData
{


    public ?string $nom = null;

    public ?\DateTimeInterface $dateDebut = null;

    public ?\DateTimeInterface $dateFin = null;

    public ?Campus $campus = null;

    public ?bool $organisateur = null;

    public ?bool $inscrit = null;

    public ?bool $NonInscrit = null;

    public ?bool $sortiePassee = null;

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->dateDebut;
    }

    public function setDateDebut(?\DateTimeInterface $dateDebut): self
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->dateFin;
    }

    public function setDateFin(?\DateTimeInterface $dateFin): self
    {
        $this->dateFin = $dateFin;

        return $this;
    }

    public function getCampus(): ?Campus
    {
        return $this->campus;
    }

    public function setCampus(Campus $campus): self
    {
        $this->campus = $campus;

        return $this;
    }

    public function isOrganisateur(): ?bool
    {
        return $this->organisateur;
    }

    public function setOrganisateur(bool $organisateur): self
    {
        $this->organisateur = $organisateur;

        return $this;
    }

    public function isInscrit(): ?bool
    {
        return $this->inscrit;
    }

    public function setInscrit(bool $inscrit): self
    {
        $this->inscrit = $inscrit;

        return $this;
    }
    public function isNotInscrit(): ?bool
        {
            return $this->NonInscrit;
        }

        public function setNotInscrit(bool $NonInscrit): self
        {
            $this->NonInscrit = $NonInscrit;

            return $this;
        }

    public function isSortiePassee(): ?bool
    {
        return $this->sortiePassee;
    }

    public function setSortiePassee(bool $sortiePassee): self
    {
        $this->sortiePassee = $sortiePassee;

        return $this;
    }

}
