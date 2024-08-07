<?php

declare(strict_types=1);

class InMemoryCatalogRepository implements CatalogRepository
{
    private array $storage = [];

    public function save(Catalog $catalog): void
    {
        // Simulate saving catalog in-memory with an ID
        $this->storage['catalog'] = $catalog;
    }

    public function findById(string $catalogId): ?Catalog
    {
        // Simulate finding catalog by ID
        return $this->storage[$catalogId] ?? null;
    }
}