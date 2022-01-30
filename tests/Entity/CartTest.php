<?php

namespace App\Tests\Entity;

use App\Entity\Cart;
use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validation;

/**
 * @covers \App\Entity\Cart
 */
class CartTest extends KernelTestCase
{
    /**
     * @group cartEntity
     */
    public function getEntity(): Cart
    {
        $product1 = (new Product())
            ->setId(998)
            ->setName('Produit-X')
            ->setDescription('Description de test')
            ->setPrice(20.50)
            ->setSlug();

        $product2 = (new Product())
            ->setId(999)
            ->setName('Produit-X')
            ->setDescription('Description de test')
            ->setPrice(10.50)
            ->setSlug();

        return (new Cart())
            ->addProductLine($product1, 1)
            ->addProductLine($product2, 2)
            ->setTotal();
    }

    /**
     * @group cartEntity
     */
    public function assertHasErrors(Cart $cart, int $number = 0)
    {
        self::bootKernel();
        $validator = Validation::createValidator();
        $errors = $validator->validate($cart);
        $messages = [];
        /** @var ConstraintViolation $error */
        foreach($errors as $error) {
            $messages[] = $error->getPropertyPath() . ' => ' . $error->getMessage();
        }
        $this->assertCount($number, $errors, implode(', ', $messages));
    }

    /**
     * @group cartEntity
     */
    public function testValidEntity()
    {
        $this->assertHasErrors($this->getEntity(), 0);
    }

    /**
     * @group cartEntity
     */
    public function testInvalidBlankNameEntity()
    {
        $this->assertHasErrors($this->getEntity()->setTotal(null), 0);
    }

    /**
     * @group cartEntity
     */
    public function testInvalidProductLineEntity()
    {
        $product3 = (new Product())
            ->setId(997)
            ->setName('Produit-X')
            ->setDescription('Description de test')
            ->setPrice(0)
            ->setSlug();

        $this->assertHasErrors($this->getEntity()->addProductLine($product3, 0), 0);
    }

    /**
     * @group cartEntity
     */
    public function testValidTotalEntity()
    {
        $this->assertEquals($this->getEntity()->getTotal(), 41.50);
    }

}
