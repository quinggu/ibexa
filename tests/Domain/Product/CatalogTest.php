<?php

declare(strict_types=1);

namespace tests\Domain\Product;

use Ibexa\Domain\Product\Catalog;
use Ibexa\Domain\Product\Price;
use Ibexa\Domain\Product\Product;
use PHPUnit\Framework\TestCase;

class CatalogTest extends TestCase
{
    public function testGetTotalAmount(): void
    {
        $product1 = new Product('P001', new Price(500, 'PLN'), 1);
        $product2 = new Product('P002', new Price(300, 'PLN'), 2);

        $catalog = new Catalog();
        $catalog->addProduct($product1);
        $catalog->addProduct($product2);

        $this->assertEquals(1100, $catalog->getTotalAmount());
    }
}