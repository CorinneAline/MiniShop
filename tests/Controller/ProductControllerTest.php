<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * @covers \App\Controller\ProductController
 */
class ProductControllerTest extends WebTestCase
{
    /**
     * @group productController
     */
    public function testListProducts()
    {
        $client = static::createClient();

        $client->request('GET', '/product/list');

        $this->assertResponseIsSuccessful();
        $this->assertPageTitleContains('Mini Shop - Products list');
    }

    /**
     * @group productController
     */
    public function testShowProductDetails()
    {
        $client = static::createClient();
        $client->request('GET', '/product/show/produit-1');

        $this->assertResponseIsSuccessful();

        $product = $client->getRequest()->attributes->get('product');

        $this->assertEquals('Produit-1', $product->getName());
        $this->assertEquals('produit-1', $product->getSlug());
        $this->assertEquals('1.jpg', $product->getImage());
    }
}
