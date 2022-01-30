<?php

namespace App\Service;

use App\Entity\Product;
use Doctrine\Persistence\ManagerRegistry;

class ProductService
{
    public function __construct(private ManagerRegistry $managerRegistry){}

    public function getProductOrdered(string $orderBy): array
    {
        return $this->managerRegistry->getRepository(Product::class)
            ->getOrderedBy($orderBy);
    }

    public function getProductsCount(): int
    {
        return $this->managerRegistry->getRepository(Product::class)->getProductsCount();
    }

    public function getProduct(int $id): Product
    {
        return $this->managerRegistry->getRepository(Product::class)->find($id);
    }

    public function getProductBy(array $criteria)
    {
        return $this->managerRegistry->getRepository(Product::class)->findOneBy([
            $criteria['attribute'] => $criteria['value']
        ]);
    }

}
