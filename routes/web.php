<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LibraryController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\Admin\DatabaseBackupController;


Route::get('/', [LibraryController::class, 'home'])->name('home');

Route::middleware(['auth'])->group(function () {
    Route::match(['get','post'], '/upload', [LibraryController::class, 'uploadExcel'])->name('upload');

    Route::get('/duplicates/resolve', [LibraryController::class, 'resolveDuplicates'])->name('duplicates.resolve');
    Route::post('/duplicates/replace', [LibraryController::class, 'replaceSelected'])->name('duplicates.replace');
    Route::post('/duplicates/skip', [LibraryController::class, 'skipAll'])->name('duplicates.skip');

    Route::get('/people', [LibraryController::class, 'people'])->name('people.index');
    Route::match(['get','post'], '/add-person', [LibraryController::class, 'addPerson'])->name('people.add');
    Route::match(['get','post'], '/people/edit/{ari8mos}', [LibraryController::class, 'editPerson'])->whereNumber('ari8mos')->name('people.edit');
    Route::post('/people/delete/{ari8mos}', [LibraryController::class, 'deletePerson'])->whereNumber('ari8mos')->name('people.delete');

    Route::get('/incomplete-records', [LibraryController::class, 'incompleteRecords'])->name('people.incomplete');

    Route::get('/ajax/autocomplete/title', [LibraryController::class, 'autocompleteTitle'])->name('autocomplete.title');
    Route::get('/ajax/autocomplete/ekdoths', [LibraryController::class, 'autocompleteEkdoths'])->name('autocomplete.ekdoths');

    Route::get('/print-range', [LibraryController::class, 'printRange'])->name('print.range');
    Route::get('/print-range/data', [LibraryController::class, 'printRangeData'])->name('print.range.data');
   
    Route::view('/dashboard', 'dashboard')
    ->middleware(['auth'])
    ->name('dashboard');

});

Route::middleware(['admin'])->group(function () {
    Route::get('/activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');
    Route::get('/admin/db-backup', [App\Http\Controllers\AdminController::class, 'showBackupPage'])->name('admin.db-backup');
    Route::post('/admin/db-backup', [DatabaseBackupController::class, 'perform'])
        ->name('admin.db-backup.perform');

    Route::get('/admin/db-backup', [DatabaseBackupController::class, 'showExportForm'])
     ->name('admin.db-backup');


    Route::get('/admin/export/csv', [DatabaseBackupController::class, 'exportCsv'])
     ->name('admin.export.csv');

    });
require __DIR__.'/auth.php';
