<?php

declare(strict_types=1);

namespace App\Command;

use App\Repository\ProductRepository;
use App\Service\CsvExporterService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:export-csv', description: 'Export the products to a CSV file')]
class ExportCsvCommand extends Command
{
    public function __construct(
        private readonly CsvExporterService $csvExporterService,
        private readonly ProductRepository $productRepository,
    )
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('filename', InputArgument::OPTIONAL, 'The filename of the CSV file')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('Exporting CSV file...');
        $products = $this->productRepository->findBy([], ['name' => 'ASC']);
        if (!$products) {
            $output->writeln('No products found.');
            return Command::FAILURE;
        }

        $filename = $input->getArgument('filename');
        $csv = $this->csvExporterService->exportProducts($products, $filename);

        $output->writeln('CSV file exported successfully.');
        $output->writeln('File path: ' . $csv->getPathname());

        return Command::SUCCESS;
    }
}
