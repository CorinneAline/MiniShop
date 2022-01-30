<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class Cart
{
    #[Assert\Type('float')]
    private float $total;

    #[Assert\Type('array')]
    private array $productLines = [];

    public function getTotal(): float
    {
        return $this->total;
    }

    public function setTotal(): Cart
    {
        $this->total = $this->calculateTotal();

        return $this;
    }

    public function calculateTotal(): float
    {
        $total = 0;
        foreach($this->productLines as $productLine) {
            $total += $productLine['quantity'] * $productLine['price'];
        }

        return $total;
    }

    public function getProductLines(): array
    {
        return $this->productLines;
    }

    public function addProductLine(Product $product, $quantity): Cart
    {
        $productLine['id'] = $product->getId();
        $productLine['image'] = $product->getImage();
        $productLine['slug'] = $product->getSlug();
        $productLine['name'] = $product->getName();
        $productLine['price'] = $product->getPrice();

        $productLine['quantity'] = $quantity;

        if(!in_array($productLine, $this->productLines)) {
            $this->productLines[] = $productLine;
        }

        $this->setTotal();

        return $this;
    }

    public function updateProductLine(array $productLine, $key): Cart
    {
        $this->productLines[$key] = $productLine;

        $this->setTotal();

        return $this;
    }

    public function removeProductLine(int $key): Cart
    {
        unset($this->productLines[$key]);

        $this->setTotal();

        return $this;
    }
}
