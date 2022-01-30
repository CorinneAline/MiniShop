<?php

namespace App\Command;

use App\Service\ProductService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpKernel\KernelInterface;

class CsvExportProductCommand extends Command
{
    public function __construct(private ProductService $productService,
                                private KernelInterface $kernel)
    {
        parent::__construct();
    }

    protected static $defaultName = 'app:export-products-csv';

    protected function configure(): void
    {
        $this
            ->setName(self::$defaultName)
            ->setDescription('Imports the product in a CSV data file created in var/export')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $products = $this->productService->getProductOrdered('name');

        if(empty($products)) {
            $io->write(['No product found']);

            return Command::INVALID;
        }

        $io->write([
            'Début de l\'import',
            '============',
            'Import des produits',
        ]);

        $io->progressStart(count($products));
        $filename = 'export-products.csv';

        $handle = fopen($this->kernel->getProjectDir() . '/var/export/' . $filename, 'w+');

        // Nom des colonnes du CSV
        fputcsv($handle, [
            'Id',
            'Nom',
            'Description',
            'Prix'
        ],';');

        foreach($products as $product) {
            fputcsv($handle, [
                $product->getId(),
                $product->getName(),
                $product->getDescription(),
                $product->getPrice() . '€',
            ],';');

            $io->progressAdvance();
        }

        fclose($handle);

        $io->progressFinish();
        $io->success('Command exited cleanly !');

        return Command::SUCCESS;
    }

}
