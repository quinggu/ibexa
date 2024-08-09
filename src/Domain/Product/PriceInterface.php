<?php

declare(strict_types=1);

namespace Ibexa\Domain\Product;

interface PriceInterface
{
    public function getAmount(): int;

    public function getCurrency(): string;
}