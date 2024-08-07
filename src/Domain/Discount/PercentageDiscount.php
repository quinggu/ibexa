<?php

declare(strict_types=1);

readonly class PercentageDiscount implements DiscountInterface
{
    public function __construct(
        private int   $percentage,
        private array $applicableProducts = []
    )
    {
    }

    public function apply(array $products): int
    {
        $totalDiscount = 0;
        foreach ($products as $product) {
            if (empty($this->applicableProducts) || in_array($product->getCode(), $this->applicableProducts)) {
                $totalDiscount += ($product->getPrice()->getAmount() * $this->percentage) / 100;
            }
        }
        return $totalDiscount;
    }
}