<?php

declare(strict_types=1);

readonly class DiscountCalculatorService
{

    public function __construct(
        private array $discounts
    )
    {
    }

    public function calculateTotal(Catalog $catalog): int
    {
        $totalAmount = $catalog->getTotalAmount();

        // Apply all discounts
        foreach ($this->discounts as $discount) {
            $totalAmount -= $discount->apply($catalog->getProducts());
        }

        // Ensure that total amount is not negative
        return max(0, $totalAmount);
    }
}