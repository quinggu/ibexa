<?php

declare(strict_types=1);

namespace tests\Application;

use Ibexa\Application\Service\DiscountCalculatorService;
use Ibexa\Domain\Discount\FixedDiscount;
use Ibexa\Domain\Discount\PercentageDiscount;
use Ibexa\Domain\Discount\VolumeDiscount;
use Ibexa\Domain\Product\Catalog;
use Ibexa\Domain\Product\Price;
use Ibexa\Domain\Product\Product;
use PHPUnit\Framework\TestCase;

class DiscountCalculatorServiceTest extends TestCase
{
    public function testApplyFixedDiscount(): void
    {
        $product1 = new Product('P001', new Price(500, 'PLN'), 2);

        $catalog = new Catalog();
        $catalog->addProduct($product1);

        // Create discounts
        $discounts = [
            new FixedDiscount(100, 'PLN'),
        ];

        $calculator = new DiscountCalculatorService($discounts);

        $total = $calculator->calculateTotal($catalog);
        // Total = 2 * 500 = 1000 PLN
        // Fixed discount = 100 PLN
        // Final total = 1000 - 100 = 900 PLN
        $this->assertEquals(900, $total); // Expected result is 900 PLN
    }

    public function testApplyPercentageDiscount(): void
    {
        $product1 = new Product('P001', new Price(200, 'PLN'), 2);

        $catalog = new Catalog();
        $catalog->addProduct($product1);

        // Create discounts
        $discounts = [
            new PercentageDiscount(10, []),
        ];

        $calculator = new DiscountCalculatorService($discounts);

        $total = $calculator->calculateTotal($catalog);

        // Total = 2 * 200 = 400 PLN
        // Percentage discount = 10% of 400 PLN = 40 PLN
        // Final total = 400 - 40 = 360 PLN
        $this->assertEquals(360, $total); // Expected result is 360 PLN
    }

    public function testApplyVolumeDiscount(): void
    {
        $product1 = new Product('P001', new Price(50, 'PLN'), 10); // 10 units
        $product2 = new Product('P002', new Price(30, 'PLN'), 5);  // 5 units

        $catalog = new Catalog();
        $catalog->addProduct($product1);
        $catalog->addProduct($product2);

        // Volume discount of 100 PLN if at least 15 items are purchased
        $discounts = [
            new VolumeDiscount(100, 'PLN', 15),
        ];

        $calculator = new DiscountCalculatorService($discounts);

        $total = $calculator->calculateTotal($catalog);

        // Total = 50 * 10 + 30 * 5 = 500 + 150 = 650 PLN
        // Volume discount = 100 PLN (since 15 or more items were purchased)
        // Final total = 650 - 100 = 550 PLN
        $this->assertEquals(550, $total); // Expected result is 550 PLN
    }

    public function testCalculateTotalWithDiscounts(): void
    {
        $product1 = new Product('P001', new Price(500, 'PLN'), 1);
        $product2 = new Product('P002', new Price(300, 'PLN'), 2);

        $catalog = new Catalog();
        $catalog->addProduct($product1);
        $catalog->addProduct($product2);

        $discounts = [
            new FixedDiscount(100, 'PLN'),
            new PercentageDiscount(10, [], priority: 2, exclusive: true),
        ];

        $calculator = new DiscountCalculatorService($discounts);

        $total = $calculator->calculateTotal($catalog);

        // Initial total = 500 + 2 * 300 = 1100 PLN
        // Percentage discount = 10% of 1100 PLN = 110 PLN
        // After percentage discount = 1100 - 110 = 990 PLN
        // After applying fixed discount = 990 PLN (fixed discount is not applied because the percentage discount is exclusive)
        $this->assertEquals(990, $total); // Expected result is 990 PLN
    }

    public function testApplyPercentageDiscountWithApplicableProducts(): void
    {
        $product1 = new Product('P001', new Price(200, 'PLN'), 1);
        $product2 = new Product('P002', new Price(300, 'PLN'), 2);

        $catalog = new Catalog();
        $catalog->addProduct($product1);
        $catalog->addProduct($product2);

        $discounts = [
            new PercentageDiscount(10, ['P002']),
        ];

        $calculator = new DiscountCalculatorService($discounts);

        $total = $calculator->calculateTotal($catalog);

        // Total = 200 + 2 * 300 = 800 PLN
        // Percentage discount = 10% of 600 (2 * 300) PLN (only for P002) = 60 PLN
        // Final total = 800 - 60 = 740 PLN
        $this->assertEquals(740, $total); // Expected result is 740 PLN
    }

    public function testApplyVolumeDiscountWithExclusiveDiscount(): void
    {
        $product1 = new Product('P001', new Price(50, 'PLN'), 10); // 10 units
        $product2 = new Product('P002', new Price(30, 'PLN'), 5);  // 5 units

        $catalog = new Catalog();
        $catalog->addProduct($product1);
        $catalog->addProduct($product2);

        $discounts = [
            // Volume discount of 100 PLN if at least 15 items are purchased
            new VolumeDiscount(100, 'PLN', 15),
            // Percentage discount of 10%, exclusive
            new PercentageDiscount(10, [], priority: 2, exclusive: true),
        ];

        $calculator = new DiscountCalculatorService($discounts);

        $total = $calculator->calculateTotal($catalog);

        // Total = 50 * 10 + 30 * 5 = 500 + 150 = 650 PLN
        // Percentage discount = 10% of 650 PLN = 65 PLN
        // Volume discount is not applied because the percentage discount is exclusive
        // Final total = 650 - 65 = 585 PLN
        $this->assertEquals(585, $total); // Expected result is 585 PLN
    }

    public function testApplyVolumeDiscountWithHigherPriority(): void
    {
        $product1 = new Product('P001', new Price(50, 'PLN'), 10); // 10 units
        $product2 = new Product('P002', new Price(30, 'PLN'), 5);  // 5 units

        $catalog = new Catalog();
        $catalog->addProduct($product1);
        $catalog->addProduct($product2);

        $discounts = [
            // Volume discount of 100 PLN if at least 15 items are purchased
            new VolumeDiscount(100, 'PLN', 15, priority: 2, exclusive: false),
            // Percentage discount of 10%
            new PercentageDiscount(10, []),
        ];

        $calculator = new DiscountCalculatorService($discounts);

        $total = $calculator->calculateTotal($catalog);

        // Total = 50 * 10 + 30 * 5 = 500 + 150 = 650 PLN
        // Percentage discount = 10% of 650 PLN = 65 PLN
        // Volume discount = 100 PLN (because the total quantity is 15 or more)
        // Final total = 650 - 65 - 100 = 485 PLN
        $this->assertEquals(485, $total); // Expected result is 485 PLN
    }

    public function testApplyVolumeDiscountWithPriorityAndExclusive(): void
    {
        $product1 = new Product('P001', new Price(50, 'PLN'), 20); // 20 units
        $product2 = new Product('P002', new Price(30, 'PLN'), 10);  // 10 units

        $catalog = new Catalog();
        $catalog->addProduct($product1);
        $catalog->addProduct($product2);

        // Create a volume discount and a percentage discount. The volume discount has higher priority and is exclusive.
        $discounts = [
            // 150 PLN discount if at least 15 items are purchased
            new VolumeDiscount(150, 'PLN', 15, priority: 2, exclusive: true),
            // 10% discount
            new PercentageDiscount(10, [])
        ];

        $calculator = new DiscountCalculatorService($discounts);

        $total = $calculator->calculateTotal($catalog);

        // Expected total value after applying the volume discount with higher priority
        // Total cost before discount: (50 * 20) + (30 * 10) = 1000 + 300 = 1300
        // Volume discount: 150 PLN (with higher priority and exclusive, so applied instead of percentage discount)
        $this->assertEquals(1150, $total); // 1300 - 150 = 1150
    }
}