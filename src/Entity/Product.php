<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Assert\NotNull]
    private int $id;

    #[ORM\Column(type: 'string', length: 100, nullable: false)]
    #[Assert\NotBlank()]
    private string $name;

    #[ORM\Column(type: 'string', length: 100, nullable: false)]
    #[Assert\NotBlank()]
    private string $slug;

    #[ORM\Column(type: 'string', length: 255, nullable: false)]
    #[Assert\NotBlank()]
    #[Assert\Length(min: 10, max: 255)]
    private string $description;

    #[ORM\Column(type: 'float', nullable: false)]
    #[Assert\Type('float')]
    private float $price;

    private string $image;

    /**
     * @param int $id
     * @return Product
     */
    public function setId(int $id): Product
    {
        $this->id = $id;
        return $this;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): Product
    {
        $this->name = $name;
        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): Product
    {
        $this->description = $description;
        return $this;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function setPrice(float $price): Product
    {
        $this->price = $price;
        return $this;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function setSlug(): Product
    {
        $slugger = new AsciiSlugger();
        $slug = $slugger->slug(strtolower($this->name));
        $this->slug = $slug;
        return $this;
    }

    public function getImage(): string
    {
        // Pas terrible :-( mais rapide à condition ne pas avoir d'autres tirets dans le nom
        $productNumber = explode('-', $this->name);

        return $productNumber[1] . '.jpg';
    }

    public function __toString()
    {
        return $this->name;
    }
}
