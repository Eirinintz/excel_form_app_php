<?php

namespace App\Services;

use App\Models\Person;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ExcelImportService
{
    public function import(string $pathToFile): array
    {
        $spreadsheet = IOFactory::load($pathToFile);
        $sheet = $spreadsheet->getActiveSheet();

        // Read all rows as array; assumes first row is header
        $rows = $sheet->toArray(null, true, true, true);
        if (count($rows) < 2) {
            return [
                'added' => 0,
                'skipped_count' => 0,
                'duplicates' => [],
                'potential_insertions' => [],
            ];
        }

        $headerRow = array_shift($rows);
        $headers = [];
        foreach ($headerRow as $col => $name) {
            $headers[$col] = trim((string)$name);
        }

        $existingIds = Person::query()->pluck('ari8mosEisagoghs')->all();
        $existingIdsSet = array_fill_keys($existingIds, true);
        $seenInFile = [];

        $duplicates = [];
        $potentialInsertions = [];
        $newRecords = [];
        $skipped = 0;

        $rowNum = 1;
        foreach ($rows as $row) {
            $rowNum++;

            $get = function (string $header) use ($headers, $row) {
                foreach ($headers as $col => $name) {
                    if ($name === $header) {
                        return $row[$col] ?? null;
                    }
                }
                return null;
            };

            $rawAri8mos = $this->cleanAri8mos($get('ΑΡΙΘΜΟΣ ΕΙΣΑΓΩΓΗΣ'));
            $syggrafeas = $this->clean($get('ΣΥΓΓΡΑΦΕΑΣ'));
            $koha = $this->clean($get('ΣΥΓΓΡΑΦΕΑΣ KOHA'));

            if (($koha === null || $koha === '') && $syggrafeas) {
                $koha = $this->generateKohaFromAuthor($syggrafeas);
            }

            $ari8mos = null;
            try {
                $ari8mos = $rawAri8mos !== null ? (int)$rawAri8mos : null;
            } catch (\Throwable $e) {
                $ari8mos = null;
            }

            if (!$ari8mos) {
                $skipped++;
                continue;
            }

            if (isset($seenInFile[$ari8mos])) {
                $skipped++;
                continue;
            }
            $seenInFile[$ari8mos] = true;

            $excelData = [
                'ari8mosEisagoghs' => $ari8mos,
                'hmeromhnia_eis' => $this->cleanNumericOrText($get('ΗΜΕΡΟΜΗΝΙΑ ΕΙΣΑΓΩΓΗΣ')),
                'syggrafeas' => $this->clean($get('ΣΥΓΓΡΑΦΕΑΣ')),
                'koha' => $koha,
                'titlos' => $this->clean($get('ΤΙΤΛΟΣ')),
                'ekdoths' => $this->clean($get('ΕΚΔΟΤΗΣ')),
                'ekdosh' => $this->clean($get('ΕΚΔΟΣΗ')),
                'etosEkdoshs' => $this->cleanNumericOrText($get('ΕΤΟΣ ΕΚΔΟΣΗΣ')),
                'toposEkdoshs' => $this->clean($get('ΤΟΠΟΣ  ΕΚΔΟΣΗΣ')),
                'sxhma' => $this->clean($get('ΣΧΗΜΑ')),
                'selides' => $this->clean($get('ΣΕΛΙΔΕΣ')),
                'tomos' => $this->clean($get('ΤΟΜΟΣ')),
                'troposPromPar' => $this->clean($get('ΤΡΟΠΟΣ ΠΡΟΜΗΘΕΙΑΣ ΠΑΡΑΤΗΡΗΣΕΙΣ')),
                'ISBN' => $this->cleanNumericOrText($get('ISBN')),
                'sthlh1' => $this->cleanNumericOrText($get('Στήλη1')),
                'sthlh2' => $this->cleanNumericOrText($get('Στήλη2')),
            ];

            if (isset($existingIdsSet[$ari8mos])) {
                $existing = Person::query()->where('ari8mosEisagoghs', $ari8mos)->first();

                // Safety: if it somehow doesn't exist, treat as new
                if (!$existing) {
                    $newRecords[] = $excelData;
                    $existingIdsSet[$ari8mos] = true;
                    continue;
                }

                $isEmpty = !$existing->syggrafeas
                    && !$existing->koha
                    && !$existing->titlos
                    && !$existing->ekdoths
                    && !$existing->ekdosh
                    && !$existing->etosEkdoshs
                    && !$existing->toposEkdoshs
                    && !$existing->sxhma
                    && !$existing->selides
                    && !$existing->tomos
                    && !$existing->ISBN
                    && !$existing->sthlh1
                    && !$existing->sthlh2;

                if ($isEmpty) {
                    $potentialInsertions[] = [
                        'ari8mos' => $ari8mos,
                        'database' => [
                            'ari8mos' => $existing->ari8mosEisagoghs,
                            'hmeromhnia_eis' => $existing->hmeromhnia_eis,
                        ],
                        'excel' => $excelData,
                    ];
                    continue;
                }

                $duplicates[] = [
                    'left' => [
                        'ari8mos' => $existing->ari8mosEisagoghs,
                        'hmeromhnia_eis' => $existing->hmeromhnia_eis,
                        'syggrafeas' => $existing->syggrafeas,
                        'koha' => $existing->koha,
                        'titlos' => $existing->titlos,
                        'ekdoths' => $existing->ekdoths,
                        'ekdosh' => $existing->ekdosh,
                        'etosEkdoshs' => $existing->etosEkdoshs,
                        'toposEkdoshs' => $existing->toposEkdoshs,
                        'sxhma' => $existing->sxhma,
                        'selides' => $existing->selides,
                        'tomos' => $existing->tomos,
                        'troposPromPar' => $existing->troposPromPar,
                        'ISBN' => $existing->ISBN,
                        'sthlh1' => $existing->sthlh1,
                        'sthlh2' => $existing->sthlh2,
                    ],
                    'right' => array_merge(['ari8mos' => $ari8mos], $excelData),
                ];
                continue;
            }

            $newRecords[] = $excelData;
            $existingIdsSet[$ari8mos] = true;
        }

        // Bulk insert new records
        if (!empty($newRecords)) {
            Person::query()->insert($newRecords);
        }

        return [
            'added' => count($newRecords),
            'skipped_count' => $skipped,
            'duplicates' => $duplicates,
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
