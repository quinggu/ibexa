<?php

declare(strict_types=1);

readonly class VolumeDiscount implements DiscountInterface
{
    public function __construct(
        private int    $amount,
        private string $currency,
        private int    $minQuantity,
        private array  $applicableProducts = []
    )
    {
    }

    public function apply(array $products): int
    {
        $totalQuantity = 0;
        $totalDiscount = 0;

        foreach ($products as $product) {
            if (empty($this->applicableProducts) || in_array($product->getCode(), $this->applicableProducts)) {
                $totalQuantity += $product->getQuantity();
            }
        }

        if ($totalQuantity >= $this->minQuantity) {
            foreach ($products as $product) {
                if ($product->getPrice()->getCurrency() === $this->currency) {
                    $totalDiscount += min($this->amount, $product->getPrice()->getAmount());
                }
            }
        }
        return $totalDiscount;
    }
}