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
}