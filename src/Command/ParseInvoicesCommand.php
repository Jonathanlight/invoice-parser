<?php

declare(strict_types=1);

namespace App\Command;

use App\Service\InvoiceParser;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

#[AsCommand(name: 'app:parse')]
class ParseInvoicesCommand extends Command
{
    public function __construct(private readonly InvoiceParser $parser, private readonly TranslatorInterface $translator)
    {
        parent::__construct();
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln($this->translator->trans('parsing.start'));

        try {
            $files = InvoiceParser::typeInvoiceFiles();

            foreach ($files as $file) {
                if (!file_exists($file)) {
                    $output->writeln(
                        sprintf(
                            '<error>%s</error>',
                            $this->translator->trans('parsing.file_not_found', ['file' => $file])
                        )
                    );
                    continue;
                }

                $output->writeln($this->translator->trans('parsing.processing_file', ['file' => $file]));
                $this->parser->parse($file);
                $output->writeln($this->translator->trans('parsing.file_processed', ['file' => $file]));
            }

            $output->writeln($this->translator->trans('parsing.success'));
        } catch (\Exception $e) {
            $output->writeln('<error>'.$e->getMessage().'</error>');

            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}
