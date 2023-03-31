<?php

namespace App\Entity;

use App\Repository\VilleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: VilleRepository::class)]
class Ville
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 150)]
    #[Assert\Regex(
        "#^[a-zA-Z0-9\é\è\ê\î\ô\û\ï\ë\ü]([-]|[a-zA-Z0-9\é\è\ê\î\ô\û\ï\ë\ü]){2,}$#",
        message: 'Pas de caractère spéciale Merci !'  ,
    )]
    private ?string $nom = null;

    #[ORM\Column]
    #[Assert\Regex(
        "#^[0-9-]{5,10}$#",
        message: 'On veut du chiffres, au pire un \'-\' mais c\'est tout !',
    )]
    private ?string $codePostal = null;

    #[ORM\OneToMany(mappedBy: 'ville', targetEntity: Lieu::class, orphanRemoval: true)]
    private Collection $Lieux;

    #[ORM\OneToMany(mappedBy: 'ville', targetEntity: Sortie::class)]
    private Collection $sorties;



    public function __construct()
    {
        $this->Lieux = new ArrayCollection();
        $this->sorties = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getCodePostal(): ?string
    {
        return $this->codePostal;
    }

    public function setCodePostal(string $codePostal): self
    {
        $this->codePostal = $codePostal;

        return $this;
    }

    /**
     * @return Collection<int, Lieu>
     */
    public function getLieux(): Collection
    {
        return $this->Lieux;
    }

    public function addLieux(Lieu $lieux): self
    {
        if (!$this->Lieux->contains($lieux)) {
            $this->Lieux->add($lieux);
            $lieux->setVille($this);
        }

        return $this;
    }

    public function removeLieux(Lieu $lieux): self
    {
        if ($this->Lieux->removeElement($lieux)) {
            // set the owning side to null (unless already changed)
            if ($lieux->getVille() === $this) {
                $lieux->setVille(null);
            }
        }

        return $this;
    }
    public function __toString()
    {

        return $this->nom;
    }

    /**
     * @return Collection<int, Sortie>
     */
    public function getSorties(): Collection
    {
        return $this->sorties;
    }

    public function addSorty(Sortie $sorty): self
    {
        if (!$this->sorties->contains($sorty)) {
            $this->sorties->add($sorty);
            $sorty->setVille($this);
        }

        return $this;
    }

    public function removeSorty(Sortie $sorty): self
    {
        if ($this->sorties->removeElement($sorty)) {
            // set the owning side to null (unless already changed)
            if ($sorty->getVille() === $this) {
                $sorty->setVille(null);
            }
        }

        return $this;
    }


}
