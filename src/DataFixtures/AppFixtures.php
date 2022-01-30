<?php

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $loremIpsum = 'Aliquam sollicitudin faucibus aenean vel tellus massa! 
        Taciti dis sociis phasellus? Cras habitasse litora quisque proin nisi consequat 
        pharetra condimentum eros? Duis inceptos, suscipit ridiculus dictum eu pharetra 
        euismod tincidunt.';

        for ($i = 1; $i <= 12; $i++) {
            $product = new Product();
            $product->setName('Produit-' . $i);
            $product->setSlug();
            $product->setDescription($loremIpsum);
            $product->setPrice(round($this->randomFloat(10, 100), 2));

            $manager->persist($product);
        }

        $manager->flush();
    }

    private function randomFloat($min, $max)
    {
        return random_int($min, $max - 1) + (random_int(0, PHP_INT_MAX - 1) / PHP_INT_MAX);
    }
}
