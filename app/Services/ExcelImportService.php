<?php

namespace App\Services;

use App\Models\Person;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\IReadFilter;

class ExcelChunkFilter implements IReadFilter
{
    private int $startRow = 0;
    private int $endRow = 0;

    public function setRows(int $startRow, int $chunkSize): void
    {
        $this->startRow = $startRow;
        $this->endRow   = $startRow + $chunkSize;
    }

    public function readCell($column, $row, $worksheetName = ''): bool
    {
        return $row >= $this->startRow && $row < $this->endRow;
    }
}

class ExcelImportService
{
    public function import(string $pathToFile): array
    {
        /* =========================
         * Reader setup
         * ========================= */
        $reader = IOFactory::createReaderForFile($pathToFile);
        $reader->setReadDataOnly(true);

        $chunkSize = 500;
        $startRow  = 1;

        $filter = new ExcelChunkFilter();
        $reader->setReadFilter($filter);

        /* =========================
         * State
         * ========================= */
        $headers = [];
        $firstRow = true;

        $batch = [];
        $batchSize = 500;

        $seenInFile = [];

        // IMPORTANT: lightweight duplicates only
        $duplicates = [];
        $potentialInsertions = [];

        $addedCount = 0;
        $skipped = 0;

        /* =========================
         * Count rows (safe)
         * ========================= */
        $infoReader = IOFactory::createReaderForFile($pathToFile);
        $infoReader->setReadDataOnly(true);

        $infoSpreadsheet = $infoReader->load($pathToFile);
        $totalRows = $infoSpreadsheet->getActiveSheet()->getHighestRow();

        $infoSpreadsheet->disconnectWorksheets();
        unset($infoSpreadsheet, $infoReader);
        gc_collect_cycles();

        /* =========================
         * Main loop
         * ========================= */
        do {
            $filter->setRows($startRow, $chunkSize);

            $spreadsheet = $reader->load($pathToFile);
            $sheet = $spreadsheet->getActiveSheet();

            foreach ($sheet->getRowIterator() as $row) {

                $cellIterator = $row->getCellIterator();
                $cellIterator->setIterateOnlyExistingCells(false);

                $rowData = [];
                foreach ($cellIterator as $cell) {
                    $rowData[$cell->getColumn()] = $cell->getValue();
                }

                /* =========================
                 * Header row
                 * ========================= */
                if ($firstRow) {
                    foreach ($rowData as $col => $name) {
                        $headers[$col] = preg_replace(
                            '/\s+/u',
                            ' ',
                            trim(str_replace("\u{00A0}", ' ', (string) $name))
                        );
                    }
                    $firstRow = false;
                    continue;
                }

                /* =========================
                 * Header getter
                 * ========================= */
                $get = function (string $header) use ($headers, $rowData) {
                    $header = preg_replace(
                        '/\s+/u',
                        ' ',
                        trim(str_replace("\u{00A0}", ' ', $header))
                    );

                    foreach ($headers as $col => $name) {
                        if ($name === $header) {
                            return $rowData[$col] ?? null;
                        }
                    }
                    return null;
                };

                /* =========================
                 * Required key
                 * ========================= */
                $rawAri8mos = $this->cleanAri8mos($get('ΑΡΙΘΜΟΣ ΕΙΣΑΓΩΓΗΣ'));
                if (!$rawAri8mos) {
                    $skipped++;
                    continue;
                }

                $ari8mos = (int) $rawAri8mos;

                /* =========================
                 * File duplicates
                 * ========================= */
                if (isset($seenInFile[$ari8mos])) {
                    $skipped++;
                    $duplicates[] = [
                        'type'    => 'file',
                        'ari8mos' => $ari8mos,
                        'row'     => $row->getRowIndex(),
                    ];
                    continue;
                }
                $seenInFile[$ari8mos] = true;

                /* =========================
                 * Build Excel data
                 * ========================= */
                $syggrafeas = $this->clean($get('ΣΥΓΓΡΑΦΕΑΣ'));
                $koha = $this->clean($get('ΣΥΓΓΡΑΦΕΑΣ KOHA'))
                    ?? $this->generateKohaFromAuthor($syggrafeas);

                $excelData = [
                    'ari8mosEisagoghs' => $ari8mos,
                    'hmeromhnia_eis'   => $this->cleanNumericOrText($get('ΗΜΕΡΟΜΗΝΙΑ ΕΙΣΑΓΩΓΗΣ')),
                    'syggrafeas'       => $syggrafeas,
                    'koha'             => $koha,
                    'titlos'           => $this->clean($get('ΤΙΤΛΟΣ')),
                    'ekdoths'          => $this->clean($get('ΕΚΔΟΤΗΣ')),
                    'ekdosh'           => $this->clean($get('ΕΚΔΟΣΗ')),
                    'etosEkdoshs'      => $this->cleanNumericOrText($get('ΕΤΟΣ ΕΚΔΟΣΗΣ')),
                    'toposEkdoshs'     => $this->clean($get('ΤΟΠΟΣ  ΕΚΔΟΣΗΣ')),
                    'sxhma'            => $this->clean($get('ΣΧΗΜΑ')),
                    'selides'          => $this->clean($get('ΣΕΛΙΔΕΣ')),
                    'tomos'            => $this->clean($get('ΤΟΜΟΣ')),
                    'troposPromPar'    => $this->clean($get('ΤΡΟΠΟΣ ΠΡΟΜΗΘΕΙΑΣ ΠΑΡΑΤΗΡΗΣΕΙΣ')),
                    'ISBN'             => $this->cleanNumericOrText($get('ISBN')),
                    'sthlh1'           => $this->cleanNumericOrText($get('Στήλη1')),
                    'sthlh2'           => $this->cleanNumericOrText($get('Στήλη2')),
                ];

                /* =========================
                 * DB duplicates (LIGHTWEIGHT)
                 * ========================= */
                if ($person = Person::where('ari8mosEisagoghs', $ari8mos)->first()) {
                    $skipped++;
                    $duplicates[] = [
                        'type'    => 'database',
                        'ari8mos' => $ari8mos,
                        'excel'   => $excelData, // OK: only for duplicates
                        'database' => $person->toArray(),
                    ];
                    unset($excelData);
                    continue;
                }

                /* =========================
                 * Batch insert
                 * ========================= */
                $batch[] = $excelData;
                $addedCount++;

                if (count($batch) >= $batchSize) {
                    Person::insert($batch);
                    $batch = [];
                }

                unset($excelData);
            }

            $spreadsheet->disconnectWorksheets();
            unset($spreadsheet, $sheet, $rowData);
            gc_collect_cycles();

            $startRow += $chunkSize;

        } while ($startRow <= $totalRows);

        if (!empty($batch)) {
            Person::insert($batch);
        }

        /* =========================
         * Result
         * ========================= */
        return [
            'added'                => $addedCount,
            'skipped_count'        => $skipped,
            'duplicates'           => $duplicates,
            'potential_insertions' => $potentialInsertions,
        ];
    }


     private function clean($value): ?string
    {
        if ($value === null) return null;
        $v = trim((string)$value);
        return $v === '' ? null : $v;
    }

    private function cleanAri8mos($value): ?string
    {
        if ($value === null) return null;
        // Handles 115011.0 etc
        if (is_numeric($value)) {
            return (string)intval((float)$value);
        }
        $v = trim((string)$value);
        return $v === '' ? null : $v;
    }

    private function cleanNumericOrText($value): ?string
    {
        if ($value === null) return null;

        if (is_float($value) || is_int($value)) {
            $f = (float)$value;
            if (floor($f) == $f) {
                return (string)intval($f);
            }
            $v = trim((string)$value);
            return $v === '' ? null : $v;
        }

        $v = trim((string)$value);
        return $v === '' ? null : $v;
    }

    private function generateKohaFromAuthor(?string $author): ?string
    {
        if (!$author) return null;
        if (strpos($author, ',') === false && strpos($author, '，') === false) return null;

        $author = str_replace('，', ',', $author);
        $parts = array_values(array_filter(array_map('trim', explode(',', $author)), fn($p) => $p !== ''));
        if (count($parts) < 2) return null;

        $surname = $parts[0];
        $name = $parts[1];
        $extra = count($parts) > 2 ? implode(' ', array_slice($parts, 2)) : '';

        $res = trim($name . ' ' . $surname . ' ' . $extra);
        return $res === '' ? null : $res;
    }
}
