<?php

declare(strict_types=1);

namespace Ibexa\Domain\Discount;

interface DiscountInterface
{
    public function apply(array $products): int;
    public function getPriority(): int;
    public function isExclusive(): bool;
}