<?php

declare(strict_types=1);

interface DiscountInterface
{
    public function apply(array $products): int;
}