<?php

namespace App\Enum;

enum FileEnum: string
{
    case JSON = 'json';
    case XML = 'xml';
    case CSV = 'csv';
    case XLSX = 'xlsx';

    /**
     * @return FileEnum[]
     */
    public static function choices(): array
    {
        return [
            'app.file.types.json' => self::JSON,
            'app.file.types.xml' => self::XML,
            'app.file.types.csv' => self::CSV,
            'app.file.types.xlsx' => self::XLSX,
        ];
    }
}
