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

        $discount = new PercentageDiscount(10);

        $this->assertEquals(110, $discount->apply([$product1, $product2]));
    }
}