<?php

declare(strict_types=1);

namespace tests\Infrastructure\Repository;

use Catalog;
use InMemoryCatalogRepository;
use PHPUnit\Framework\TestCase;
use Price;
use Product;

class CatalogRepositoryTest extends TestCase
{
    public function testSaveAndFindById(): void
    {
        $product1 = new Product('P001', new Price(500, 'PLN'), 1);
        $product2 = new Product('P002', new Price(300, 'PLN'), 2);

        $catalog = new Catalog();
        $catalog->addProduct($product1);
        $catalog->addProduct($product2);

        $repository = new InMemoryCatalogRepository();
        $repository->save($catalog);

        $retrievedCatalog = $repository->findById('catalog');
        $this->assertNotNull($retrievedCatalog);
        $this->assertEquals(1100, $retrievedCatalog->getTotalAmount());
    }
}