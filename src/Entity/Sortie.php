<?php

namespace App\Entity;

use App\Repository\SortieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Config\Twig\DateConfig;

#[ORM\Entity(repositoryClass: SortieRepository::class)]
class Sortie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\NotNull]
    #[Assert\Regex(
        "#^[a-zA-Z0-9\é\è\ê\î\ô\û\ï\ë\ü\ ]([-]|[a-zA-Z0-9\é\è\ê\î\ô\û\ï\ë\ü\ ]){2,}$#",
        message: 'Des chiffres et des lettres ! Et puis c\'est tout !'  ,
    )]
    private ?string $nom = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Assert\NotBlank]
    #[Assert\NotNull]
    #[Assert\GreaterThanOrEqual('today',
        message: 'La date de début de la sortie ne peut être antérieur à la date du jour ',
    )]
//    #[Assert\Callback('validerDateDebut')]
    private ?\DateTimeInterface $dateHeureDebut = null;

    #[ORM\Column]
    #[Assert\NotBlank]
    #[Assert\NotNull]
    private ?int $duree = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Assert\NotBlank]
    #[Assert\NotNull]
    #[Assert\GreaterThanOrEqual('today',
        message: 'La date de début de la sortie ne peut être antérieur à la date du jour ',
    )]
//    #[Assert\Callback("validerDate")]
     private ?\DateTimeInterface $dateLimiteInscription = null;

    #[ORM\Column(length: 500)]
    private ?string $infosSortie = null;

    #[ORM\ManyToOne(inversedBy: 'sorties')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Lieu $lieu = null;
    #[ORM\Column(length: 500)]
    private ?string $motifAnnulation = '';

    #[ORM\ManyToOne(inversedBy: 'sorties')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Campus $siteOrganisateur = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Etat $etat = null;

    #[ORM\ManyToMany(targetEntity: Utilisateur::class, mappedBy: 'sorties')]

    private Collection $participants;

    #[ORM\ManyToOne(inversedBy: 'sortiesOrganisees')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Utilisateur $organisateurs = null;

    #[ORM\Column]
    private ?int $nbInscriptionMax = null;

    #[ORM\ManyToOne(inversedBy: 'sorties')]
    private ?Ville $ville = null;

    public function __construct()
    {
//        $nbInscriptionMax=0;
        //TODO   [$nbInscriptionMax] dans les paramètres de ArrayCollection

        $this->participants = new ArrayCollection();


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

    public function getDateHeureDebut(): ?\DateTimeInterface
    {
        return $this->dateHeureDebut;
    }

    public function setDateHeureDebut(\DateTimeInterface $dateHeureDebut): self
    {
        $this->dateHeureDebut = $dateHeureDebut;

        return $this;
    }

    public function getDuree(): ?int
    {
        return $this->duree;
    }

    public function setDuree(int $duree): self
    {
        $this->duree = $duree;

        return $this;
    }

    public function getDateLimiteInscription(): ?\DateTimeInterface
    {
        return $this->dateLimiteInscription;
    }

    public function setDateLimiteInscription(\DateTimeInterface $dateLimiteInscription): self
    {
        $this->dateLimiteInscription = $dateLimiteInscription;

        return $this;
    }

    public function getInfosSortie(): ?string
    {
        return $this->infosSortie;
    }

    public function setInfosSortie(string $infosSortie): self
    {
        $this->infosSortie = $infosSortie;

        return $this;
    }

    public function getLieu(): ?Lieu
    {
        return $this->lieu;
    }

    public function setLieu(?Lieu $lieu): self
    {
        $this->lieu = $lieu;

        return $this;
    }

    public function getSiteOrganisateur(): ?Campus
    {
        return $this->siteOrganisateur;
    }

    public function setSiteOrganisateur(?Campus $siteOrganisateur): self
    {
        $this->siteOrganisateur = $siteOrganisateur;

        return $this;
    }

    public function getEtat(): ?Etat
    {
        return $this->etat;
    }

    public function setEtat(?Etat $etat): self
    {
        $this->etat = $etat;

        return $this;
    }

    /**
     * @return Collection<int, Utilisateur>
     */
    public function getParticipants(): Collection
    {
        return $this->participants;
    }

    public function addParticipant(Utilisateur $participant): self
    {
        if (!$this->participants->contains($participant)) {
            $this->participants->add($participant);
            $participant->addSorty($this);
        }

        return $this;
    }

    public function removeParticipant(Utilisateur $participant): self
    {
        if ($this->participants->removeElement($participant)) {
            $participant->removeSorty($this);
        }

        return $this;
    }

    public function getOrganisateurs(): ?Utilisateur
    {
        return $this->organisateurs;
    }

    public function setOrganisateurs(?Utilisateur $organisateurs): self
    {
        $this->organisateurs = $organisateurs;

        return $this;
    }

    public function getNbInscriptionMax(): ?int
    {
        return $this->nbInscriptionMax;
    }

    public function setNbInscriptionMax(int $nbInscriptionMax): self
    {
        $this->nbInscriptionMax = $nbInscriptionMax;

        return $this;
    }

    public function getVille(): ?Ville
    {
        return $this->ville;
    }

    /**
     * @return string|null
     */
    public function getMotifAnnulation(): ?string
    {
        return $this->motifAnnulation;
    }

    /**
     * @param string|null $motifAnnulation
     */
    public function setMotifAnnulation(?string $motifAnnulation): void
    {
        $this->motifAnnulation = $motifAnnulation;
    }

    public function setVille(?Ville $ville): self
    {
        $this->ville = $ville;

        return $this;
    }

//    /**
//     * @Assert\Callback
//     */
//    public function validerDate(ExecutionContextInterface $context): void
//    {
//        if($this->getDateLimiteInscription() > $this->getDateHeureDebut()){
//            $context->buildViolation('La date d\'inscription doit toujours se terminer avant la date de début de sortir')
//                ->atpath('dateLimiteInscription')
//                ->addViolation();
//        }
//    }

}
