<?php

declare(strict_types=1);

namespace tests\Domain\Discount;

use PHPUnit\Framework\TestCase;
use Price;
use Product;
use VolumeDiscount;

class VolumeDiscountTest extends TestCase
{
    public function testApplyVolumeDiscount(): void
    {
        $product1 = new Product('P001', new Price(500, 'PLN'), 1);
        $product2 = new Product('P002', new Price(300, 'PLN'), 2);

        $discount = new VolumeDiscount(100, 'PLN', 3);

        $this->assertEquals(100, $discount->apply([$product1, $product2]));
    }

    public function testApplyVolumeDiscountWithApplicableProducts(): void
    {
        $product1 = new Product('P001', new Price(500, 'PLN'), 1);
        $product2 = new Product('P002', new Price(300, 'PLN'), 2);
        $product3 = new Product('P003', new Price(200, 'PLN'), 1);

        // The discount is to be applied only to products P002 and P003, with min. 2 products
        $discount = new VolumeDiscount(100, 'PLN', 2, ['P002', 'P003']);

        // The discount should be applied because the sum of P002 and P003 gives 3 products (PLN 100 discount)
        $this->assertEquals(100, $discount->apply([$product1, $product2, $product3]));
    }

    public function testApplyVolumeDiscountWithoutMeetingMinQuantity(): void
    {
        $product1 = new Product('P001', new Price(500, 'PLN'), 1);
        $product2 = new Product('P002', new Price(300, 'PLN'), 1);

        // The discount is to be applied only to P002 products with min. 2 products
        $discount = new VolumeDiscount(100, 'PLN', 2, ['P002']);

        // The discount should not be applied because there is only 1 product P002
        $this->assertEquals(0, $discount->apply([$product1, $product2]));
    }
}