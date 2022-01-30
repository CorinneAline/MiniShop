<?php

namespace App\Controller;

use App\Entity\Product;
use App\Service\ProductService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api', name: 'api_')]
class ApiController extends AbstractController
{
    public function __construct(
        private ProductService $productService,
        private SerializerInterface $serializer) {}

    #[Route('/products', name: 'products', methods: ['GET'])]
    public function apiGetProducts(): JsonResponse
    {
        $products = $this->productService->getProductOrdered('name');

        foreach ($products as $product) {
            $data[] = $this->serializer->serialize($product, 'json');
        }

        return new JsonResponse([
            'data' => $data,
            'status' => Response::HTTP_OK
        ]);
    }
}
