<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\Table(name="user")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\NotBlank(message="Veuillez renseignez un Identifiant.",groups={"registration"})
     * @Assert\Length(
     *      min="3",max="50",
     *      minMessage="3 caractères minimum",
     *      maxMessage="50 caractères maximum",groups={"registration"}
     * )
     */
    private $identifiant;

    /**
     * @ORM\Column(type="string", length=150)
     * @Assert\NotBlank(message="Veuillez renseignez un Email.",groups={"registration","connexion"})
     * @Assert\Email(
     *      message="L'adresse email '{{value}}' n'est pas valide"
     *      , groups={"registration","connexion"}
     * )
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=150)
     */
    private $password;

    /**
     * @var array
     * 
     * @ORM\Column(type="json")
     */
    private $roles = ['ROLE_USER'];

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateregistered;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Ad", mappedBy="user", orphanRemoval=true)
     */
    private $ads;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Ad")
     */
    private $fav;

    /**
     * @ORM\Column(type="integer")
     */
    private $money = 0;

    /*
    * @var string|null
    * @Assert\NotBlank(message="Veuillez renseignez un mot de passe.",groups={"registration","connexion"})
    */
    private $plainPassword = null;

    public function __construct()
    {
        $this->ads = new ArrayCollection();
        $this->fav = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdentifiant(): ?string
    {
        return $this->identifiant;
    }

    public function setIdentifiant(?string $identifiant): self
    {
        $this->identifiant = $identifiant;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getDateregistered(): ?\DateTimeInterface
    {
        return $this->dateregistered;
    }

    public function setDateregistered(\DateTimeInterface $dateregistered): self
    {
        $this->dateregistered = $dateregistered;

        return $this;
    }

    /**
     * @return Collection|Ad[]
     */
    public function getAds(): Collection
    {
        return $this->ads;
    }

    public function addAd(Ad $ad): self
    {
        if (!$this->ads->contains($ad)) {
            $this->ads[] = $ad;
            $ad->setUser($this);
        }

        return $this;
    }

    public function removeAd(Ad $ad): self
    {
        if ($this->ads->contains($ad)) {
            $this->ads->removeElement($ad);
            // set the owning side to null (unless already changed)
            if ($ad->getUser() === $this) {
                $ad->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Ad[]
     */
    public function getFav(): Collection
    {
        return $this->fav;
    }

    public function addFav(Ad $fav): self
    {
        if (!$this->fav->contains($fav)) {
            $this->fav[] = $fav;
        }

        return $this;
    }

    public function removeFav(Ad $fav): self
    {
        if ($this->fav->contains($fav)) {
            $this->fav->removeElement($fav);
        }

        return $this;
    }

    public function getMoney(): ?int
    {
        return $this->money;
    }

    public function setMoney(?int $money): self
    {
        $this->money = $money;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getSalt()
    {
        return null;
    }

    /**
     * @inheritDoc
     */
    public function eraseCredentials()
    {
        $this->plainPassword = null;
    }

    /**
     * @inheritDoc
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * @inheritDoc
     */
    public function getusername()
    {
        return $this->email;
    }

    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    public function setPlainPassword($plainPassword)
    {
        $this->plainPassword = $plainPassword;
        return $this;
    }

}