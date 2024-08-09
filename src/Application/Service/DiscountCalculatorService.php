<?php

declare(strict_types=1);

namespace Ibexa\Application\Service;

use Ibexa\Domain\Discount\DiscountInterface;
use Ibexa\Domain\Product\Catalog;

readonly class DiscountCalculatorService
{

    public function __construct(private array $discounts)
    {
    }

    public function calculateTotal(Catalog $catalog): int
    {
        // Retrieve the list of products and discounts from the catalog
        $products = $catalog->getProducts();

        // Sort the discounts so that exclusive discounts come first
        $sortedDiscounts = $this->sortDiscounts($this->discounts);

        // Calculate the total amount of all products before applying discounts
        $totalAmount = $catalog->getTotalAmount();

        // Calculate the total amount of discounts to apply
        $totalDiscountAmount = $this->calculateTotalDiscount($products, $sortedDiscounts);

        // Return the final amount after subtracting the total discounts from the total amount
        return $totalAmount - $totalDiscountAmount;
    }

    /**
     * Sort discounts with exclusive ones first, then by priority.
     */
    private function sortDiscounts(array $discounts): array
    {
        usort($discounts, function (DiscountInterface $a, DiscountInterface $b) {
            // Compare exclusivity: exclusive discounts come first
            $exclusiveComparison = (int)$b->isExclusive() - (int)$a->isExclusive();

            // If exclusivity is the same, compare by priority (higher priority first)
            return $exclusiveComparison ?: $b->getPriority() <=> $a->getPriority();
        });

        return $discounts;
    }

    private function calculateTotalDiscount(array $products, array $discounts): int
    {
        $totalDiscountAmount = 0;
        $exclusiveDiscountApplied = false; // Flag to ensure only one exclusive discount is applied

        foreach ($discounts as $discount) {
            if ($discount->isExclusive()) {
                // Apply only one exclusive discount if it hasn't been applied yet
                if (!$exclusiveDiscountApplied) {
                    $totalDiscountAmount += $discount->apply($products);
                    $exclusiveDiscountApplied = true; // Mark that an exclusive discount has been applied
                }
            } else if (!$exclusiveDiscountApplied) {
                $totalDiscountAmount += $discount->apply($products);
            }
        }

        return $totalDiscountAmount;
    }
}