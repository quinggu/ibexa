<?php

declare(strict_types=1);

readonly class FixedDiscount implements DiscountInterface
{
    public function __construct(
        private int    $amount,
        private string $currency,
        private array  $applicableProducts = []
    )
    {
    }

    public function apply(array $products): int
    {
        $totalDiscount = 0;
        foreach ($products as $product) {
            if (empty($this->applicableProducts) || in_array($product->getCode(), $this->applicableProducts)) {
                if ($product->getPrice()->getCurrency() === $this->currency) {
                    $totalDiscount += min($this->amount, $product->getPrice()->getAmount());
                }
            }
        }

        return $totalDiscount;
    }
}