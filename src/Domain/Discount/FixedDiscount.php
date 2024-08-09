<?php

declare(strict_types=1);

namespace Ibexa\Domain\Discount;

use Ibexa\Domain\Product\ProductInterface;

class FixedDiscount extends AbstractDiscount
{
    public function __construct(
        private readonly int    $amount,
        private readonly string $currency,
        protected array         $applicableProducts = [],
        protected int           $priority = 0,
        protected bool          $exclusive = false
    ) {
        parent::__construct($applicableProducts, $priority, $exclusive);
    }

    /**
     * Applies the discount to the given list of products.
     */
    public function apply(array $products): int
    {
        $totalApplicableAmount = $this->calculateTotalApplicableAmount($products);

        return min($this->amount, $totalApplicableAmount);
    }

    /**
     * Calculates the total amount applicable for the discount based on the provided products.
     */
    private function calculateTotalApplicableAmount(array $products): int
    {
        $totalAmount = 0;

        foreach ($products as $product) {
            if ($this->isProductApplicable($product) && $this->hasMatchingCurrency($product)) {
                $totalAmount += $product->getPrice()->getAmount();
            }
        }

        return $totalAmount;
    }

    /**
     * Checks if the product's currency matches the discount's currency.
     */
    private function hasMatchingCurrency(ProductInterface $product): bool
    {
        return $product->getPrice()->getCurrency() === $this->currency;
    }
}