<?php

declare(strict_types=1);

namespace tests\Application;

use Catalog;
use DiscountCalculatorService;
use FixedDiscount;
use PercentageDiscount;
use PHPUnit\Framework\TestCase;
use Price;
use Product;

class DiscountCalculatorServiceTest extends TestCase
{
    public function testCalculateTotalWithDiscounts(): void
    {
        $product1 = new Product('P001', new Price(500, 'PLN'), 1);
        $product2 = new Product('P002', new Price(300, 'PLN'), 2);

        $catalog = new Catalog();
        $catalog->addProduct($product1);
        $catalog->addProduct($product2);

        $discounts = [
            new FixedDiscount(100, 'PLN'),
            new PercentageDiscount(10)
        ];

        $calculatorService = new DiscountCalculatorService($discounts);
        $total = $calculatorService->calculateTotal($catalog);

        $this->assertEquals(810, $total); // 500 + 600 - 100 - 90 = 810
    }
}