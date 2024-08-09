<?php

declare(strict_types=1);

namespace Ibexa\Infrastructure\Repository;

use Ibexa\Domain\Product\Catalog;

interface CatalogRepository
{
    public function save(Catalog $catalog): void;

    public function findById(string $catalogId): ?Catalog;
}