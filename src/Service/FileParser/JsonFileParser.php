<?php

namespace App\Service\FileParser;

use App\Entity\Invoice;
use App\Repository\InvoiceRepository;
use Doctrine\ORM\EntityManagerInterface;

class JsonFileParser implements FileParserInterface
{
    public function __construct(public InvoiceRepository $invoiceRepository, public EntityManagerInterface $em)
    {
    }

    public function parse(string $filePath): void
    {
        $datas = $this->parseJsonFile($filePath);

        if (null === $datas) {
            throw new \RuntimeException('Error parsing JSON file.');
        }

        foreach ($datas as $data) {
            $amount = $data['montant'];
            $currency = $data['devise'];
            $name = $data['nom'];

            $invoice = $this->invoiceRepository->getInvoiceByName($name);

            if (!$invoice instanceof Invoice) {
                $invoice = new Invoice();
                $invoice->setName($name);
            }

            $invoice->setAmount($amount);
            $invoice->setCurrency($currency);

            $this->em->persist($invoice);
            $this->em->flush();
        }
    }

    /**
     * @return array<array{montant: float, devise: string, nom: string, date: string}>|null
     */
    public function parseJsonFile(string $filePath): ?array
    {
        if (!file_exists($filePath)) {
            throw new \InvalidArgumentException("File does not exist: $filePath");
        }

        $jsonContent = file_get_contents($filePath);

        if (empty($jsonContent)) {
            throw new \RuntimeException("The file is empty: $filePath");
        }

        $data = json_decode($jsonContent, true);

        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new \RuntimeException('Error parsing JSON: '.json_last_error_msg());
        }

        return $this->extractInvoiceData($data);
    }

    /**
     * @param array<array{montant: float, devise: string, nom: string, date: string}> $data
     *
     * @return array<array{montant: float, devise: string, nom: string, date: string}>
     */
    private function extractInvoiceData(array $data): array
    {
        $invoices = [];

        foreach ($data as $item) {
            if ($item['montant'] > 0 && '' !== $item['devise'] && '' !== $item['nom'] && false !== strtotime($item['date'])) {
                $invoices[] = [
                    'montant' => $item['montant'],
                    'devise' => $item['devise'],
                    'nom' => $item['nom'],
                    'date' => $item['date'],
                ];
            } else {
                throw new \RuntimeException('Invalid data format in JSON.');
            }
        }

        return $invoices;
    }
}
