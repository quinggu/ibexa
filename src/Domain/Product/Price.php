<?php

declare(strict_types=1);

namespace Ibexa\Domain\Product;

readonly class Price implements PriceInterface
{

    public function __construct(
        private int    $amount,
        private string $currency
    ) {
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }
}