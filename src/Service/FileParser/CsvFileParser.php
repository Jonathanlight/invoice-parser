<?php

namespace App\Service\FileParser;

use App\Entity\Invoice;
use App\Repository\InvoiceRepository;
use Doctrine\ORM\EntityManagerInterface;

class CsvFileParser implements FileParserInterface
{
    public function __construct(public InvoiceRepository $invoiceRepository, public EntityManagerInterface $em)
    {
    }

    public function parse(string $filePath): void
    {
        $d = array_map(function ($r) {
            return str_getcsv($r, "\t");
        }, file($filePath));

        foreach ($d as $record) {
            $amount = (float) $record[0];
            $currency = $record[1];
            $name = $record[2];

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
}
