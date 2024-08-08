<?php

declare(strict_types=1);

readonly class DiscountCalculatorService
{

    public function __construct(private Catalog $catalog)
    {
    }

    public function calculateTotal(): int
    {
        $products = $this->catalog->getProducts();
        $discounts = $this->catalog->getDiscounts();

        // Sortujemy zniżki tak, aby ekskluzywne były na początku
        usort($discounts, function (DiscountInterface $a, DiscountInterface $b) {
            if ($a->isExclusive() && !$b->isExclusive()) {
                return -1; // Ekskluzywne zniżki mają wyższy priorytet
            }
            if (!$a->isExclusive() && $b->isExclusive()) {
                return 1;  // Ekskluzywne zniżki mają wyższy priorytet
            }
            // Jeśli oba są ekskluzywne lub oba są nieekskluzywne, sortujemy według priorytetu
            return $a->getPriority() <=> $b->getPriority();
        });

        $totalAmount = array_reduce($products, function (int $carry, Product $product) {
            return $carry + ($product->getPrice()->getAmount() * $product->getQuantity());
        }, 0);

        $totalDiscountAmount = 0;
        $exclusiveDiscountApplied = false;

        foreach ($discounts as $discount) {
            if ($discount->isExclusive()) {
                if (!$exclusiveDiscountApplied) {
                    $totalDiscountAmount += $discount->apply($products);
                    $exclusiveDiscountApplied = true;
                }
            } else {
                if (!$exclusiveDiscountApplied) {
                    $totalDiscountAmount += $discount->apply($products);
                }
            }
        }

        return $totalAmount - $totalDiscountAmount;
    }
}