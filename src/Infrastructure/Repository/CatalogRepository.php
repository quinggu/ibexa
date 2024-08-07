<?php

declare(strict_types=1);

interface CatalogRepository
{
    public function save(Catalog $catalog): void;

    public function findById(string $catalogId): ?Catalog;
}