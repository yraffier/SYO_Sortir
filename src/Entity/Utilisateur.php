<?php

namespace App\Entity;

use App\Repository\UtilisateurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UtilisateurRepository::class)]
#[UniqueEntity(fields: ['username'], message: 'There is already an account with this username')]
class Utilisateur implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Assert\Regex(
        "#^[a-zA-Z0-9]([._-](?![._-])|[a-zA-Z0-9]){3,18}$#",
        message: 'Des minuscules, des majuscules, des chiffres, et \'.\' \'-\' \'_\', C\'est tout ce dont tu as besoin !',
    )]
    private ?string $username = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    #[Assert\Regex(
        "#^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$ %^&*-._]).{6,100}$#",
        message: 'Plus c\'est long plus c\'est bon ! Mais avec une minuscule, une majuscule, un caractère spéciale et un chiffre c\'est encore meilleur ! ',
    )]
    private ?string $password = null;
    private ?string $plainPassword = null;

    #[ORM\Column(length: 100)]
    #[Assert\Regex(
        "#^[a-zA-Z\é\è\ê\î\ô\û\ï\ë\ü]([-]|[a-zA-Z\é\è\ê\î\ô\û\ï\ë\ü]){2,}$#",
        message: 'Tu ne sais plus écrire ton nom, évite les caractères spéciales et les chiffres !',
    )]
    private ?string $nom = null;

    #[ORM\Column(length: 100)]
    #[Assert\Regex(
        "#^[a-zA-Z\é\è\ê\î\ô\û\ï\ë\ü]([-]|[a-zA-Z\é\è\ê\î\ô\û\ï\ë\ü]){2,}$#",
        message: 'Reste simple ! N\'utilise que des lettres pour ton prénom, éventuellement un \'-\'',
    )]
    private ?string $prenom = null;

    #[ORM\Column(length: 20)]
    #[Assert\Regex(
        "#^[\+]?([0-9]{2}[.\-\ \_]?){4,6}$#",
        message: 'Des chiffres, des chiffres, des chiffres, des chiffres !!',
    )]
    private ?string $telephone = null;

    #[ORM\Column(length: 200)]
    #[Assert\Regex(
        "#^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$#",
        message: 'Pas besoin de caractères spéciales, tu l\'es déjà ! <3'
    )]
    private ?string $mail = null;

    #[ORM\Column]
    private ?bool $administrateur = null;

    #[ORM\Column]
    private ?bool $actif = null;

    #[ORM\ManyToMany(targetEntity: Sortie::class, inversedBy: 'participants')]
    private Collection $sorties;

    #[ORM\OneToMany(mappedBy: 'organisateurs', targetEntity: Sortie::class, orphanRemoval: true)]
    private Collection $sortiesOrganisees;

    #[ORM\ManyToOne(inversedBy: 'stagiaires')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Campus $campus = null;


    public function __construct()
    {
        $this->sorties = new ArrayCollection();
        $this->sortiesOrganisees = new ArrayCollection();
        $this->administrateur = false;
        $this->actif = true;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->username;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(string $mail): self
    {
        $this->mail = $mail;

        return $this;
    }

    public function isAdministrateur(): ?bool
    {
        return $this->administrateur;
    }

    public function setAdministrateur(bool $administrateur): self
    {
        $this->administrateur = $administrateur;

        return $this;
    }

    public function isActif(): ?bool
    {
        return $this->actif;
    }

    public function setActif(bool $actif): self
    {
        $this->actif = $actif;

        return $this;
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
        }

        return $this;
    }

    public function removeSorty(Sortie $sorty): self
    {
        $this->sorties->removeElement($sorty);

        return $this;
    }

    /**
     * @return Collection<int, Sortie>
     */
    public function getSortiesOrganisees(): Collection
    {
        return $this->sortiesOrganisees;
    }

    public function addSortiesOrganisee(Sortie $sortiesOrganisee): self
    {
        if (!$this->sortiesOrganisees->contains($sortiesOrganisee)) {
            $this->sortiesOrganisees->add($sortiesOrganisee);
            $sortiesOrganisee->setOrganisateurs($this);
        }

        return $this;
    }

    public function removeSortiesOrganisee(Sortie $sortiesOrganisee): self
    {
        if ($this->sortiesOrganisees->removeElement($sortiesOrganisee)) {
            // set the owning side to null (unless already changed)
            if ($sortiesOrganisee->getOrganisateurs() === $this) {
                $sortiesOrganisee->setOrganisateurs(null);
            }
        }

        return $this;
    }

    public function getCampus(): ?Campus
    {
        return $this->campus;
    }

    public function setCampus(?Campus $campus): self
    {
        $this->campus = $campus;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    /**
     * @param string|null $plainPassword
     */
    public function setPlainPassword(?string $plainPassword): self
    {
        $this->plainPassword = $plainPassword;
        return $this;
    }
    public function eraseCredantials(){
        $this->plainPassword = null;
    }
}
