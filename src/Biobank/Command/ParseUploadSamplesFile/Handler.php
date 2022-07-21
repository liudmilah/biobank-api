<?php

declare(strict_types=1);

namespace App\Biobank\Command\ParseUploadSamplesFile;

use App\Biobank\Entity\SampleData;
use App\Biobank\Entity\SpecieData;
use DomainException;
use Jawira\CaseConverter\Convert;

final class Handler
{
    /**
     * @throws \Jawira\CaseConverter\CaseConverterException
     * @return array{
     *     type:string,
     *     samples:array,
     *     species:array,
     * }
     */
    public function handle(Command $command): array
    {
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($command->file);
        $worksheet = $spreadsheet->getActiveSheet();

        /** @var string[][] $rows */
        if (empty($rows = $worksheet->toArray())
            || empty($headerRow = array_filter((array)$rows[0]))
        ) {
            throw new DomainException('Empty file.');
        }

        unset($rows[0]); // remove the header row

        $fileColumns = array_map(static fn (string $col) => (new Convert(trim($col)))->toCamel(), $headerRow);
        /** @var string[] $specieColumns */
        $specieColumns = array_keys(get_class_vars(SpecieData::class));
        /** @var string[] $sampleColumns */
        $sampleColumns = array_keys(get_class_vars(SampleData::class));
        if ($diff = array_diff($fileColumns, [...$sampleColumns, ...$specieColumns])) {
            throw new DomainException(
                'Please check your file, it contains unknown columns: ' . implode(',', array_values($diff))
            );
        }

        $species = [];
        $samples = [];
        /**
         * @var int $index
         * @var string[] $row
         */
        foreach ($rows as $index => $row) {
            foreach ($specieColumns as $col) {
                $pos = array_search($col, $fileColumns, true);
                $species[$index][$col] = $pos === false ? null : $row[$pos];
            }
            foreach ($sampleColumns as $col) {
                $pos = array_search($col, $fileColumns, true);
                $samples[$index][$col] = $pos === false ? null : $row[$pos];
                $samples[$index]['specieName'] = $row[(int)array_search('nameLat', $fileColumns, true)];
            }
        }

        return [
            'species' => $species,
            'samples' => $samples,
            'type' => $command->type,
        ];
    }
}
