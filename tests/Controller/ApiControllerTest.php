<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * @covers \App\Controller\ApiController
 */
class ApiControllerTest extends WebTestCase
{
    /**
     * @group apiController
     */
    public function testApiGetProducts()
    {
        $client = static::createClient();

        $client->request('GET', '/api/products');

        $response = $client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $json = json_decode($response->getContent(), true);

        $products = [];
        foreach($json['data'] as $product) {
            $products[] = json_decode($product, true);
        }

        $this->assertIsArray($products);

        foreach($products as $product) {
            $this->assertIsProduct($product);
        }

        $this->assertIsValidProduct($products[0]);
    }

    private function assertIsProduct(array $data)
    {
        $this->assertArrayHasKey('id', $data);
        $this->assertArrayHasKey('name', $data);
        $this->assertArrayHasKey('slug', $data);
        $this->assertArrayHasKey('image', $data);
        $this->assertArrayHasKey('price', $data);
    }

    private function assertIsValidProduct(array $data)
    {
        $this->assertEquals($data['name'], 'Produit-1');
        $this->assertEquals($data['slug'], 'produit-1');
    }

}
