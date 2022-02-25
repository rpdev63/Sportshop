<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ArticleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource()]
#[ORM\Entity(repositoryClass: ArticleRepository::class)]
#[UniqueEntity("name", message: "Ce nom d'article existe déjà !")]
class Article
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;


    #[ORM\Column(type: 'string', length: 100)]
    #[Assert\NotBlank(message: "Ce champs doit être rempli")]
    #[Assert\Length(min: 2, minMessage: "Veuillez saisir au moins 2 caractères.")]
    private $name;

    #[ORM\Column(type: 'string', length: 100, nullable: true)]
    private $reference;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $description;

    #[ORM\Column(type: 'integer')]
    #[Assert\NotBlank(message: "Ce champs doit être rempli et doit être un nombre entier")]
    #[Assert\Type(
        type: 'integer',
    )]
    #[Assert\Range(min: 0)]
    private $price;

    #[ORM\Column(type: 'integer')]
    #[Assert\NotBlank(message: "Ce champs doit être rempli et doit être un nombre entier")]
    #[Assert\Type(type: 'integer')]
    #[Assert\Range(min: 0)]
    private $oldPrice;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $img;

    #[ORM\Column(type: 'integer')]
    #[Assert\NotBlank(message: "Ce champs doit être rempli et doit être un nombre entier")]
    #[Assert\Range(min: 1, max: 5)]
    private $rate;

    #[ORM\Column(type: 'boolean')]
    private $isNew;

    #[ORM\ManyToOne(targetEntity: Brand::class, inversedBy: 'articles')]
    private $brand;


    #[ORM\ManyToMany(targetEntity: Category::class, inversedBy: 'articles')]

    private $categories;

    #[ORM\ManyToMany(targetEntity: Panier::class, mappedBy: 'articles')]
    private $paniers;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
        $this->paniers = new ArrayCollection();
    }



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(?string $reference): self
    {
        $this->reference = $reference;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

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

    public function getOldPrice(): ?int
    {
        return $this->oldPrice;
    }

    public function setOldPrice(int $oldPrice): self
    {
        $this->oldPrice = $oldPrice;

        return $this;
    }

    public function getImg(): ?string
    {
        return $this->img;
    }

    public function setImg(?string $img): self
    {
        $this->img = $img;

        return $this;
    }

    public function getRate(): ?string
    {
        return $this->rate;
    }

    public function setRate(string $rate): self
    {
        $this->rate = $rate;

        return $this;
    }

    public function getIsNew(): ?bool
    {
        return $this->isNew;
    }

    public function setIsNew(bool $isNew): self
    {
        $this->isNew = $isNew;

        return $this;
    }

    public function getBrand(): ?Brand
    {
        return $this->brand;
    }

    public function setBrand(?Brand $brand): self
    {
        $this->brand = $brand;

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
        $this->categories->removeElement($category);

        return $this;
    }

    /**
     * @return Collection|Panier[]
     */
    public function getPaniers(): Collection
    {
        return $this->paniers;
    }

    public function addPanier(Panier $panier): self
    {
        if (!$this->paniers->contains($panier)) {
            $this->paniers[] = $panier;
            $panier->addArticle($this);
        }

        return $this;
    }

    public function removePanier(Panier $panier): self
    {
        if ($this->paniers->removeElement($panier)) {
            $panier->removeArticle($this);
        }

        return $this;
    }
}
