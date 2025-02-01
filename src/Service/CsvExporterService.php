<?php

namespace App\Service;

use App\Entity\Product;
use InvalidArgumentException;
use NumberFormatter;
use SplFileInfo;
use SplFileObject;

class CsvExporterService
{
    public function __construct(
        private readonly string $savePath,
    )
    {
    }

    /**
     * @param Product[] $products
     */
    public function exportProducts(array $products, ?string $filename): SplFileInfo
    {
        if (!is_dir($this->savePath)) {
            mkdir($this->savePath, 0755, true);
        }
        if (!$filename) {
            $filename = 'products-' . date('Y-m-d') . '.csv';
        }

        if (pathinfo($filename, PATHINFO_EXTENSION) !== 'csv') {
            throw new InvalidArgumentException('The filename must have a .csv extension.');
        }

        $csv = new SplFileObject($this->savePath . $filename, 'w');

        $csv->fputcsv(['id', 'name', 'description', 'price', 'slug', 'image']);

        $formatter = new NumberFormatter('fr', NumberFormatter::CURRENCY);

        $formatter->setAttribute(NumberFormatter::ROUNDING_MODE, NumberFormatter::ROUND_FLOOR);
        $formatter->setAttribute(NumberFormatter::MIN_FRACTION_DIGITS, 2);
        $formatter->setAttribute(NumberFormatter::MAX_FRACTION_DIGITS, 2);

        foreach ($products as $product) {
            $csv->fputcsv([
                $product->getId(),
                $product->getName(),
                $product->getDescription(),
                $formatter->formatCurrency($product->getPrice(), 'EUR'),
                $product->getSlug(),
                $product->getImage(),
            ]);
        }

        return $csv;
    }
}