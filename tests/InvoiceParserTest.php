<?php

declare(strict_types=1);

namespace App\Tests;

use App\Repository\InvoiceRepository;
use App\Service\FileParser\FileParserFactory;
use App\Service\FileParser\FileParserInterface;
use App\Service\InvoiceParser;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class InvoiceParserTest extends KernelTestCase
{
    private EntityManagerInterface $entityManager;
    private FileParserFactory $fileParserFactory;
    private InvoiceRepository $invoiceRepository;
    private FileParserInterface $fileParser;
    private InvoiceParser $invoiceParser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->fileParserFactory = $this->createMock(FileParserFactory::class);
        $this->invoiceRepository = $this->createMock(InvoiceRepository::class);
        $this->fileParser = $this->createMock(FileParserInterface::class);

        $this->fileParserFactory
            ->method('createParser')
            ->willReturn($this->fileParser);

        $this->invoiceParser = new InvoiceParser(
            $this->entityManager,
            $this->fileParserFactory,
            $this->invoiceRepository
        );
    }

    /**
     * @dataProvider filePathProvider
     */
    public function testParse(string $filePath): void
    {
        $this->fileParser->expects($this->once())
            ->method('parse')
            ->with($filePath);

        $this->invoiceParser->parse($filePath);
    }

    public function filePathProvider(): array
    {
        return [
            ['data/invoices.json'],
            ['data/invoices.csv'],
        ];
    }
}
