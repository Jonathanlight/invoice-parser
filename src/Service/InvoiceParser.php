<?php

declare(strict_types=1);

namespace App\Service;

use App\Enum\FileEnum;
use App\Repository\InvoiceRepository;
use App\Service\FileParser\FileParserFactory;
use Doctrine\ORM\EntityManagerInterface;

class InvoiceParser
{
    public function __construct(
        private EntityManagerInterface $em,
        private FileParserFactory $fileParserFactory,
        private InvoiceRepository $invoiceRepository,
    ) {
    }

    public function parse(string $filePath): void
    {
        $fileParser = $this->fileParserFactory->createParser($filePath, $this->em, $this->invoiceRepository);

        $fileParser->parse($filePath);
    }

    /**
     * @return string[]
     */
    public static function typeInvoiceFiles(): array
    {
        $pathName = 'data/invoices.';

        $files = [];

        foreach (FileEnum::choices() as $typeEnum) {
            $files[] = $pathName.$typeEnum->value;
        }

        return $files;
    }
}
