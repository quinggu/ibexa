<?php

declare(strict_types=1);

abstract readonly class AbstractDiscount implements DiscountInterface
{
    public function __construct(
        private array $applicableProducts = [],
        private int   $priority = 0,
        private bool  $exclusive = false
    ) {
    }

    /**
     * Checks if a product is applicable for the discount.
     */
    protected function isProductApplicable(ProductInterface $product): bool
    {
        $productCode = $product->getCode();

        return empty($this->applicableProducts) || in_array($productCode, $this->applicableProducts, true);
    }

    /**
     * Returns the priority of this discount.
     */
    public function getPriority(): int
    {
        return $this->priority;
    }

    /**
     * Checks if this discount is exclusive.
     */
    public function isExclusive(): bool
    {
        return $this->exclusive;
    }
}