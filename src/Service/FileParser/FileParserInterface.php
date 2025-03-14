<?php

namespace App\Service\FileParser;

interface FileParserInterface
{
    public function parse(string $filePath): void;
}
