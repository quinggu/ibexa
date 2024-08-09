<?php

declare(strict_types=1);

namespace Ibexa\Domain\Product;

interface ProductInterface
{
    public function getCode(): string;

    public function getPrice(): PriceInterface;

    public function getQuantity(): int;
}