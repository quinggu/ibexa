<?php

declare(strict_types=1);

interface PriceInterface
{
    public function getAmount(): int;

    public function getCurrency(): string;
}