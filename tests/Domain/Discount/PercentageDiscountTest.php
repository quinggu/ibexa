<?php

declare(strict_types=1);

namespace tests\Domain\Discount;

use PercentageDiscount;
use PHPUnit\Framework\TestCase;
use Price;
use Product;

class PercentageDiscountTest extends TestCase
{
    public function testApplyPercentageDiscount(): void
    {
        $product1 = new Product('P001', new Price(500, 'PLN'), 1);
        $product2 = new Product('P002', new Price(300, 'PLN'), 2);

        // We create a percentage discount of 10%
        $discount = new PercentageDiscount(10);

        // We calculate the discount for the products
        $discountAmount = $discount->apply([$product1, $product2]);

        // We expect a discount of PLN 110 (10% of PLN 500 + 10% of PLN 300 * 2)
        $this->assertEquals(110, $discountAmount);
    }

    public function testApplyPercentageDiscountWithApplicableProducts(): void
    {
        $product1 = new Product('P001', new Price(500, 'PLN'), 1);
        $product2 = new Product('P002', new Price(300, 'PLN'), 2);
        $product3 = new Product('P003', new Price(200, 'PLN'), 1);

        // The discount is to be applied to P002 products only
        $discount = new PercentageDiscount(10, ['P002']);

        // We calculate the discount for the products
        $discountAmount = $discount->apply([$product1, $product2, $product3]);

        // We expect a discount of PLN 60 (10% of PLN 300 x 2)
        $this->assertEquals(60, $discountAmount);
    }
}