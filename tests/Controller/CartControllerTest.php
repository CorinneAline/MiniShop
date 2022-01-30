<?php

namespace App\Tests\Controller;

use App\Entity\Cart;
use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockFileSessionStorage;

/**
 * @covers \App\Controller\CartController
 */
class CartControllerTest extends WebTestCase
{
    private function getMockProduct(): Product
    {
        return (new Product())
            ->setId(1)
            ->setName('Produit-1')
            ->setDescription('Description de test')
            ->setPrice(10.50)
            ->setSlug();
    }

    private function getMockCart(): Cart
    {
        return (new Cart())
            ->addProductLine($this->getMockProduct(), 3);
    }

    /**
     * @group cartController
     */
    public function testMockCartIsValidCart(): void
    {
        $mockCart = $this->getMockCart();

        $this->assertEquals([[
            "id" => 1,
            "image" => "1.jpg",
            "slug" => "produit-1",
            "name" => "Produit-1",
            "price" => 10.5,
            "quantity" => 3
        ]], $mockCart->getProductLines());

        $this->assertEquals(10.5 * 3, $mockCart->getTotal());
    }

    /**
     * @group cartController
     */
    public function testAddToCart(): void
    {
        $quantity = 2;

        $client = static::createClient();
        $client->request('GET', '/cart/add/produit-1', [
            'quantity' => $quantity
        ]);

        $session = new Session(new MockFileSessionStorage());
        $session->set('mini_shop_cart', $this->getMockCart());

        $client->followRedirect();
        $this->assertResponseIsSuccessful();
        $this->assertPageTitleContains('Mini Shop - Products list');
    }

    /**
     * @group cartController
     */
    public function testCheckout(): void
    {
        $client = static::createClient();

        $client->request('GET', '/cart/checkout');

        $client->followRedirect();
        $this->assertResponseIsSuccessful();
        $this->assertPageTitleContains('Mini Shop - Home');
    }

}
