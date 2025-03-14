<?php

namespace App\Service\FileParser;

use App\Enum\FileEnum;
use App\Repository\InvoiceRepository;
use Doctrine\ORM\EntityManagerInterface;

class FileParserFactory
{
    public function createParser(string $filePath, EntityManagerInterface $em, InvoiceRepository $invoiceRepository): FileParserInterface
    {
        return match (true) {
            str_contains($filePath, FileEnum::JSON->value) => new JsonFileParser($invoiceRepository, $em),
            str_contains($filePath, FileEnum::CSV->value) => new CsvFileParser($invoiceRepository, $em),
            default => throw new \InvalidArgumentException('Unsupported file type'),
        };
    }
}
