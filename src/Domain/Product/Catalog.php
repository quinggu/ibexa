<?php

declare(strict_types=1);

class Catalog
{
    public function __construct(
        private array $products = []
    ) {
    }

    public function addProduct(Product $product): void
    {
        $this->products[] = $product;
    }

    public function getProducts(): array
    {
        return $this->products;
    }

    public function getTotalAmount(): int
    {
        $totalAmount = 0;
        foreach ($this->products as $product) {
            $totalAmount += $product->totalPrice();
        }
        return $totalAmount;
    }
}