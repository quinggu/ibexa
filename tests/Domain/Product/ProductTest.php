<?php

declare(strict_types=1);

namespace tests\Domain\Product;

use Ibexa\Domain\Product\Price;
use Ibexa\Domain\Product\Product;
use PHPUnit\Framework\TestCase;

class ProductTest extends TestCase
{
    public function testTotalPrice(): void
    {
        $product = new Product('P001', new Price(100, 'PLN'), 3);

        $this->assertEquals(300, $product->totalPrice());
    }
}