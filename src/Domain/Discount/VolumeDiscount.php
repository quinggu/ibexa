<?php

declare(strict_types=1);

readonly class VolumeDiscount extends AbstractDiscount
{
    public function __construct(
        private int    $amount,
        private string $currency,
        private int    $threshold,
        private array  $applicableProducts = [],
        private int    $priority = 0,
        private bool   $exclusive = false
    ) {
        parent::__construct($applicableProducts, $priority, $exclusive);
    }

    /**
     * Applies the discount to the given list of products.
     */
    public function apply(array $products): int
    {
        $totalQuantity = $this->calculateTotalQuantity($products);

        // Apply discount if total quantity meets or exceeds the threshold
        return $totalQuantity >= $this->threshold ? $this->amount : 0;
    }

    /**
     * Calculates the total quantity of applicable products.
     */
    private function calculateTotalQuantity(array $products): int
    {
        $totalQuantity = 0;

        foreach ($products as $product) {
            if ($this->isProductApplicable($product) && $this->hasMatchingCurrency($product)) {
                $totalQuantity += $product->getQuantity();
            }
        }

        return $totalQuantity;
    }

    /**
     * Checks if the product's currency matches the discount's currency.
     */
    private function hasMatchingCurrency(ProductInterface $product): bool
    {
        return $product->getPrice()->getCurrency() === $this->currency;
    }
}