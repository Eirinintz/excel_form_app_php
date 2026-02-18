<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;


class DatabaseBackupController extends Controller
{
    public function perform()
    {
        $databaseName = config('database.connections.mysql.database');
        $tables = DB::select('SHOW TABLES');
        $key = "Tables_in_" . $databaseName;

        $sql = "-- Database Backup\n";
        $sql .= "-- Generated: " . now() . "\n\n";

        foreach ($tables as $table) {
            $tableName = $table->$key;

            $sql .= "DROP TABLE IF EXISTS `$tableName`;\n";

            $createTable = DB::select("SHOW CREATE TABLE `$tableName`")[0]->{'Create Table'};
            $sql .= $createTable . ";\n\n";

            $rows = DB::table($tableName)->get();

            foreach ($rows as $row) {
                $values = array_map(function ($value) {
                    return isset($value)
                        ? "'" . addslashes($value) . "'"
                        : "NULL";
                }, (array) $row);

                $sql .= "INSERT INTO `$tableName` VALUES (" . implode(',', $values) . ");\n";
            }

            $sql .= "\n\n";
        }

        $fileName = 'backup_' . now()->format('Y_m_d_H_i_s') . '.sql';
        $path = storage_path('app/' . $fileName);

        file_put_contents($path, $sql);


        return response()->download($path)
                 ->deleteFileAfterSend(true);

    }

    public function exportCsv(Request $request)
{
    $tableName = $request->query('table'); // gets selected table

    // validate table
    $databaseName = config('database.connections.mysql.database');
    $tablesRaw = DB::select('SHOW TABLES');
    $key = "Tables_in_" . $databaseName;
    $availableTables = array_map(fn($t) => $t->$key, $tablesRaw);

    if (!in_array($tableName, $availableTables)) {
        abort(403, 'Table not allowed.');
    }

    $fileName = $tableName . '_' . now()->format('Y_m_d_H_i_s') . '.csv';
    $path = storage_path('app/' . $fileName);

    $handle = fopen($path, 'w');
    $rows = DB::table($tableName)->get();

    if ($rows->isNotEmpty()) {
        fputcsv($handle, array_keys((array) $rows->first()));
        foreach ($rows as $row) {
            fputcsv($handle, (array) $row);
        }
    }

    fclose($handle);

    return response()->download($path)->deleteFileAfterSend(true);
    }



    public function showExportForm()
{
    $databaseName = config('database.connections.mysql.database');
    $tablesRaw = DB::select('SHOW TABLES');
    $key = "Tables_in_" . $databaseName;

    $tables = [];
    foreach ($tablesRaw as $table) {
        $tables[] = $table->$key;
    }

    return view('admin.db-backup', compact('tables'));
}



}
