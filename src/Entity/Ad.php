<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AdRepository")
 * @ORM\Table(name="ad")
 */
class Ad
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\NotBlank(message="Veuillez renseigner un titre à votre annonce !")
     * @Assert\Length(
     *     min="2", max="50",
     *     minMessage = "1 caractère minimum !",
     *     maxMessage = "100 caractères maximum !",
     *     groups={"search", "create"}
     *)
     *
     * @ORM\Column(type="string", length=100)
     */
    private $title;

    /**
     * @Assert\NotBlank(message="Veuillez renseigner une description à votre annonce !")
     * @Assert\Length(
     *     min="2", max="1000",
     *     minMessage = "1 caractère minimum !",
     *     maxMessage = "1000 caractères maximum !",
     *     groups={"search", "create"}
     *)
     *
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @Assert\NotBlank(message="Veuillez renseigner une ville à votre annonce !")
     * @Assert\Length(
     *     min="1", max="150",
     *     minMessage = "1 caractère minimum !",
     *     maxMessage = "150 caractères maximum !",
     *     groups={"search", "create"}
     *)
     *
     * @ORM\Column(type="string", length=150)
     */
    private $city;

    /**
     * @Assert\NotBlank(message="Veuillez renseigner un code postal (ex: 49000) à votre annonce !")
     * @Assert\Length(
     *     min="5", max="5",
     *     exactMessage="Un code postal contient 5 chiffres !",
     *     groups={"search", "create"}
     * )
     *
     * @ORM\Column(type="integer")
     */
    private $zip;

    /**
     * @Assert\NotBlank(message="Veuillez renseigner un prix à votre annonce !")
     * @Assert\Type(
     *    type="integer",
     *    message="test",
     *    groups={"search", "create"}
     * )
     *
     * @ORM\Column(type="integer")
     */
    private $price;

    /**
     * @ORM\Column(type="datetime")
     */
    private $datecreated;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Category", inversedBy="ads")
     * @ORM\JoinTable(name="ads_categories")
     */
    private $categories;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getZip(): ?int
    {
        return $this->zip;
    }

    public function setZip(int $zip): self
    {
        $this->zip = $zip;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getDatecreated(): ?\DateTimeInterface
    {
        return $this->datecreated;
    }

    public function setDatecreated(\DateTimeInterface $datecreated): self
    {
        $this->datecreated = $datecreated;

        return $this;
    }

    /**
     * @return Collection|Category[]
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Category $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories[] = $category;
        }

        return $this;
    }

    public function removeCategory(Category $category): self
    {
        if ($this->categories->contains($category)) {
            $this->categories->removeElement($category);
        }

        return $this;
    }
}
