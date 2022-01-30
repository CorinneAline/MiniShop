<?php

namespace App\Controller;

use App\Entity\Product;
use App\Service\ProductService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/product', name: 'product_')]
class ProductController extends AbstractController
{
    public function __construct(
        private ProductService $productService) {}

    #[Route('/list', name: 'list', methods: ['GET'])]
    public function listProducts(): Response
    {
        $products = $this->productService->getProductOrdered('name');

        return $this->render('product/index.html.twig', [
            'products' => $products
        ]);
    }

    #[Route('/show/{slug}', name: 'show', methods: ['GET'])]
    public function showProductDetails(Product $product): Response
    {
        return $this->render('product/show.html.twig', [
            'product' => $product
        ]);
    }

    public function getProductsCount(): Response
    {
        $productsCount = $this->productService->getProductsCount();

        return new Response($productsCount);
    }
}
