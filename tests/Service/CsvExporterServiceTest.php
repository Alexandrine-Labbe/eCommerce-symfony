<?php

declare(strict_types=1);

namespace App\Tests\Service;

use App\Entity\Product;
use App\Service\CsvExporterService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CsvExporterServiceTest extends KernelTestCase
{
    public function testExportProducts()
    {
        self::bootKernel();
        $container = static::getContainer();
        $csvExporter = $container->get(CsvExporterService::class);

        $product = new Product();
        $product
            ->setId(1)
            ->setName('Product 1')
            ->setDescription('Description 1')
            ->setPriceCents(1000)
            ->setSlug('product-1');

        $products = [
            $product,
        ];

        $expectedCsvContent = "id,name,description,price,slug,image\n1,\"Product 1\",\"Description 1\",\"10,00 €\",product-1,https://placehold.co/100\n";

        $csvFile = $csvExporter->exportProducts($products, 'test-export.csv');
        $this->assertFileExists($csvFile->getRealPath());
        $csvContent = file_get_contents($csvFile->getRealPath());
        $this->assertEquals($expectedCsvContent, $csvContent);
    }
}
