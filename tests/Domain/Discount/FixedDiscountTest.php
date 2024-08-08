<?php

declare(strict_types=1);

namespace tests\Domain\Discount;

use FixedDiscount;
use PHPUnit\Framework\TestCase;
use Price;
use Product;

class FixedDiscountTest extends TestCase
{
    public function testApplyFixedDiscount(): void
    {
        $product1 = new Product('P001', new Price(500, 'PLN'), 1);
        $product2 = new Product('P002', new Price(300, 'PLN'), 2);

        $discount = new FixedDiscount(100, 'PLN');

        $this->assertEquals(100, $discount->apply([$product1, $product2]));
    }

    public function testApplyFixedDiscountWithApplicableProducts(): void
    {
        $product1 = new Product('P001', new Price(500, 'PLN'), 1);
        $product2 = new Product('P002', new Price(300, 'PLN'), 2);
        $product3 = new Product('P003', new Price(200, 'PLN'), 1);

        // The discount is to be applied only to products P001 and P003
        $discount = new FixedDiscount(100, 'PLN', ['P001', 'P003']);

        // The discount should only be applied to P001 and P003 (PLN 100 discount)
        $this->assertEquals(100, $discount->apply([$product1, $product2, $product3]));
    }
}