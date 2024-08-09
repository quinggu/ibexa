<?php

declare(strict_types=1);

namespace Ibexa\Domain\Discount;

use Ibexa\Domain\Product\ProductInterface;

class PercentageDiscount extends AbstractDiscount
{
    public function __construct(
        private readonly int $percentage,
        protected array      $applicableProducts = [],
        protected int        $priority = 0,
        protected bool       $exclusive = false
    ) {
        parent::__construct($applicableProducts, $priority, $exclusive);
    }

    /**
     * Applies the discount to the given list of products.
     */
    public function apply(array $products): int
    {
        $totalDiscount = 0;

        foreach ($products as $product) {
            if ($this->isProductApplicable($product)) {
                $totalDiscount += $this->calculateProductDiscount($product);
            }
        }

        return $totalDiscount;
    }

    /**
     * Calculates the discount amount for a given product.
     */
    private function calculateProductDiscount(ProductInterface $product): int
    {
        $productTotalAmount = $product->getPrice()->getAmount() * $product->getQuantity();

        return ($productTotalAmount * $this->percentage) / 100;
    }
}