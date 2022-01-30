<?php

namespace App\Tests\Entity;

use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validation;

/**
 * @covers \App\Entity\Product
 */
class ProductTest extends KernelTestCase
{
    /**
     * @group productEntity
     */
    public function getEntity(): Product
    {
        return (new Product())
            ->setId(999)
            ->setName('Produit-X')
            ->setDescription('Description de test')
            ->setPrice(10.50)
            ->setSlug();
    }

    /**
     * @group productEntity
     */
    public function assertHasErrors(Product $product, int $number = 0)
    {
        self::bootKernel();
        $validator = Validation::createValidator();
        $errors = $validator->validate($product);
        $messages = [];
        /** @var ConstraintViolation $error */
        foreach($errors as $error) {
            $messages[] = $error->getPropertyPath() . ' => ' . $error->getMessage();
        }
        $this->assertCount($number, $errors, implode(', ', $messages));
    }

    /**
     * @group productEntity
     */
    public function testValidEntity()
    {
        $this->assertHasErrors($this->getEntity(), 0);
    }

    /**
     * @group productEntity
     */
    public function testInvalidBlankNameEntity()
    {
        $this->assertHasErrors($this->getEntity()->setName(''), 0);
    }

    /**
     * @group productEntity
     */
    public function testInvalidBlankDescriptionEntity()
    {
        $this->assertHasErrors($this->getEntity()->setDescription(''), 0);
    }

    /**
     * @group productEntity
     */
    public function testInvalidFloatPriceEntity()
    {
        $this->assertHasErrors($this->getEntity()->setPrice(10), 0);
    }
}
