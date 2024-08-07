<?php

declare(strict_types=1);

readonly class Product implements ProductInterface
{
    public function __construct(
        private string $code,
        private Price  $price,
        private int    $quantity
    )
    {
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getPrice(): Price
    {
        return $this->price;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function totalPrice(): int
    {
        return $this->price->getAmount() * $this->quantity;
    }
}