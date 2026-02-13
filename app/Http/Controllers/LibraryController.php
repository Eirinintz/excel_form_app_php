<?php

namespace App\Http\Controllers;

use App\Models\Person;
use App\Models\UploadLog;
use App\Services\ExcelImportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class LibraryController extends Controller
{
    public function home()
    {
        return view('home');
    }

    public function people(Request $request)
    {
        $qs = Person::query()
            ->whereNotNull('ari8mosEisagoghs')
            ->orderBy('ari8mosEisagoghs');

        $search = trim((string)$request->query('search', ''));
        $searchCategory = $request->query('search_category', 'all');

        if ($search !== '') {
            if ($searchCategory === 'all') {
                $qs->where(function ($q) use ($search) {
                    $q->where('titlos', 'like', "%{$search}%")
                      ->orWhere('syggrafeas', 'like', "%{$search}%");
                    if (ctype_digit($search)) {
                        $q->orWhere('ari8mosEisagoghs', (int)$search);
                    }
                });
            } elseif ($searchCategory === 'ari8mos') {
                if (ctype_digit($search)) {
                    $qs->where('ari8mosEisagoghs', (int)$search);
                } else {
                    $qs->whereRaw('1=0');
                }
            } elseif (in_array($searchCategory, ['hmeromhnia_eis','titlos','syggrafeas','ekdoths','ISBN'], true)) {
                $qs->where($searchCategory, 'like', "%{$search}%");
            }
        }

        $fromNum = $request->query('from_num');
        $toNum = $request->query('to_num');
        if ($fromNum !== null && $toNum !== null && ctype_digit((string)$fromNum) && ctype_digit((string)$toNum)) {
            $qs->whereBetween('ari8mosEisagoghs', [(int)$fromNum, (int)$toNum]);
        }

        $pageObj = $qs->paginate(200)->withQueryString();

        if ($request->ajax()) {
            $html = view('partials.people_table_rows', ['pageObj' => $pageObj])->render();
            return response()->json([
                'html' => $html,
                'has_previous' => $pageObj->previousPageUrl() !== null,
                'has_next' => $pageObj->nextPageUrl() !== null,
                'current_page' => $pageObj->currentPage(),
                'total_pages' => $pageObj->lastPage(),
            ]);
        }

        return view('people.index', [
            'pageObj' => $pageObj,
            'search' => $search,
            'search_category' => $searchCategory,
        ]);
    }

    public function addPerson(Request $request)
    {
        $last = Person::query()->max('ari8mosEisagoghs');
        $nextNumber = ((int)$last) + 1;

        $prefillAri8mos = $request->query('ari8mos');
        $submitted = $request->boolean('submitted');
        $allComplete = $request->boolean('all_complete');

        $person = null;
        if ($prefillAri8mos && ctype_digit((string)$prefillAri8mos)) {
            $person = Person::query()->where('ari8mosEisagoghs', (int)$prefillAri8mos)->first();
        }

        if ($request->isMethod('post')) {
            $data = $request->all();
            $rules = $this->personRules();

            $validator = Validator::make($data, $rules);
            $validator->validate();

            $wasFillingIncomplete = (bool)$prefillAri8mos;

            if ($person) {
                $person->fill($data);
                $person->ari8mosEisagoghs = (int)$prefillAri8mos;
                $person->save();
            } else {
                $p = new Person();
                $p->fill($data);
                $p->ari8mosEisagoghs = $prefillAri8mos ? (int)$prefillAri8mos : $nextNumber;
                $p->save();
            }

            if ($wasFillingIncomplete) {
                $nextIncomplete = $this->nextIncomplete();
                if ($nextIncomplete) {
                    return redirect()->route('people.add', [
                        'ari8mos' => $nextIncomplete->ari8mosEisagoghs,
                        'submitted' => 1,
                    ]);
                }
                return redirect()->route('people.add', ['submitted' => 1, 'all_complete' => 1]);
            }

            return redirect()->route('people.add', ['submitted' => 1]);
        }

        $nextForDisplay = $prefillAri8mos ?: $nextNumber;

        return view('people.add', [
            'person' => $person,
            'next_number' => $nextForDisplay,
            'submitted' => $submitted,
            'is_editing' => (bool)$prefillAri8mos,
            'all_complete' => $allComplete,
        ]);
    }

    public function editPerson(Request $request, int $ari8mos)
    {
        $person = Person::query()->where('ari8mosEisagoghs', $ari8mos)->firstOrFail();

        if ($request->isMethod('post')) {
            $validator = Validator::make($request->all(), $this->personRules());
            $validator->validate();

            $person->fill($request->all());
            $person->save();

            return redirect()->route('people.index')->with('status', "Record #{$ari8mos} updated successfully!");
        }

        return view('people.edit', ['person' => $person]);
    }

    public function deletePerson(Request $request, int $ari8mos)
    {
        $user = $request->user();
        if (!$user || !$user->is_admin) {
            abort(403, 'You are not allowed to delete records.');
        }

        if ($request->isMethod('post')) {
            Person::query()->where('ari8mosEisagoghs', $ari8mos)->delete();
            return redirect()->route('people.index')->with('status', "Record #{$ari8mos} deleted successfully!");
        }

        abort(405);
    }

    public function uploadExcel(Request $request, ExcelImportService $importer)
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'excel_file' => ['required', 'file', 'mimes:xls,xlsx'],
            ]);

            $file = $request->file('excel_file');
            $stored = $file->storeAs('uploads', Str::random(8).'_'.$file->getClientOriginalName(), 'local');

            $result = $importer->import(Storage::disk('local')->path($stored));

            session([
                'duplicates' => $result['duplicates'],
                'potential_insertions' => $result['potential_insertions'],
                'new_records_count' => $result['added'],
                'skipped_count' => $result['skipped_count'],
                'upload_filename' => $file->getClientOriginalName(),
            ]);

            if (!empty($result['duplicates']) || !empty($result['potential_insertions'])) {
                return redirect()->route('duplicates.resolve');
            }

            UploadLog::create([
                'user_id' => $request->user()->id,
                'uploaded_at' => now(),
                'filename' => $file->getClientOriginalName(),
                'rows_added' => $result['added'],
                'rows_updated' => 0,
            ]);

            return view('upload.result', [
                'added_count' => $result['added'],
                'updated_count' => 0,
                'duplicate_count' => 0,
                'skipped_count' => $result['skipped_count'],
                'total_records' => Person::query()->count(),
            ]);
        }

        return view('upload.form');
    }

    public function resolveDuplicates()
    {
        $duplicates = session('duplicates', []);
        $potential = session('potential_insertions', []);

        if (empty($duplicates) && empty($potential)) {
            return redirect()->route('upload')->with('status', 'No duplicates or potential insertions to resolve.');
        }

        return view('duplicates.resolve', [
            'duplicates' => $duplicates,
            'potential_insertions' => $potential,
            'duplicate_count' => count($duplicates),
            'insertion_count' => count($potential),
        ]);
    }

    public function replaceSelected(Request $request)
    {
        $request->validate([
            'duplicate_ids' => ['array'],
            'insertion_ids' => ['array'],
        ]);

        $duplicates = session('duplicates', []);
        $potential = session('potential_insertions', []);

        $selectedDup = array_map('strval', $request->input('duplicate_ids', []));
        $selectedIns = array_map('strval', $request->input('insertion_ids', []));

        $updated = 0;
        $inserted = 0;

        foreach ($duplicates as $dup) {
            $ari8mos = (string)($dup['left']['ari8mos'] ?? '');
            if (!in_array($ari8mos, $selectedDup, true)) continue;

            $person = Person::query()->where('ari8mosEisagoghs', (int)$ari8mos)->first();
            if (!$person) continue;

            $right = $dup['right'] ?? [];
            $person->fill($right);
            $person->save();
            $updated++;
        }

        foreach ($potential as $ins) {
            $ari8mos = (string)($ins['ari8mos'] ?? '');
            if (!in_array($ari8mos, $selectedIns, true)) continue;

            $person = Person::query()->where('ari8mosEisagoghs', (int)$ari8mos)->first();
            if (!$person) continue;

            $excel = $ins['excel'] ?? [];
            $person->fill($excel);
            $person->save();
            $inserted++;
        }

        $newCount = session('new_records_count', 0);
        $skippedCount = session('skipped_count', 0);
        $filename = session('upload_filename', 'Excel Upload');

        UploadLog::create([
            'user_id' => $request->user()->id,
            'uploaded_at' => now(),
            'filename' => $filename,
            'rows_added' => $newCount,
            'rows_updated' => $updated + $inserted,
        ]);

        $this->clearImportSession();

        return view('upload.result', [
            'added_count' => $newCount,
            'updated_count' => $updated + $inserted,
            'duplicate_count' => 0,
            'skipped_count' => $skippedCount,
            'total_records' => Person::query()->count(),
        ]);
    }

    public function skipAll(Request $request)
    {
        $duplicates = session('duplicates', []);
        $potential = session('potential_insertions', []);
        $newCount = session('new_records_count', 0);
        $skippedCount = session('skipped_count', 0);
        $filename = session('upload_filename', 'Excel Upload');

        UploadLog::create([
            'user_id' => $request->user()->id,
            'uploaded_at' => now(),
            'filename' => $filename,
            'rows_added' => $newCount,
            'rows_updated' => 0,
        ]);

        $this->clearImportSession();

        $totalSkipped = count($duplicates) + count($potential);

        return view('upload.result', [
            'added_count' => $newCount,
            'updated_count' => 0,
            'duplicate_count' => 0,
            'skipped_count' => $skippedCount + $totalSkipped,
            'total_records' => Person::query()->count(),
        ]);
    }

    public function incompleteRecords()
    {
        $q = Person::query()->whereNull('syggrafeas')
            ->whereNull('koha')
            ->whereNull('titlos')
            ->whereNull('ekdoths')
            ->whereNull('ekdosh')
            ->whereNull('etosEkdoshs')
            ->whereNull('toposEkdoshs')
            ->whereNull('sxhma')
            ->whereNull('selides')
            ->whereNull('tomos')
            ->whereNull('ISBN')
            ->whereNull('sthlh1')
            ->whereNull('sthlh2')
            ->orderBy('ari8mosEisagoghs');

        $count = $q->count();
        $first = (clone $q)->first();
        $records = (clone $q)->limit(100)->get();

        return view('people.incomplete', [
            'count' => $count,
            'records' => $records,
            'total_records' => Person::query()->count(),
            'first_incomplete' => $first,
        ]);
    }

    public function autocompleteTitle(Request $request)
    {
        $q = (string)$request->query('q', '');
        $results = Person::query()
            ->where('titlos', 'like', "%{$q}%")
            ->distinct()
            ->limit(10)
            ->pluck('titlos')
            ->values();

        return response()->json(['results' => $results]);
    }

    public function autocompleteEkdoths(Request $request)
    {
        $q = (string)$request->query('q', '');
        $results = Person::query()
            ->where('ekdoths', 'like', "%{$q}%")
            ->distinct()
            ->limit(10)
            ->pluck('ekdoths')
            ->values();

        return response()->json(['results' => $results]);
    }

    public function printRange(Request $request)
    {
        $from = $request->query('from_num');
        $to = $request->query('to_num');

        if (!$from || !$to || !ctype_digit((string)$from) || !ctype_digit((string)$to)) {
            return redirect()->route('people.index')->with('status', 'Παρακαλώ εισάγετε έγκυρο αριθμό αφετηρίας και τέλους');
        }

        $from = (int)$from;
        $to = (int)$to;

        $total = Person::query()
            ->whereBetween('ari8mosEisagoghs', [$from, $to])
            ->count();

        return view('print.range', [
            'from_num' => $from,
            'to_num' => $to,
            'total_count' => $total,
        ]);
    }

    public function printRangeData(Request $request)
    {
        $from = $request->query('from_num');
        $to = $request->query('to_num');
        $offset = (int)$request->query('offset', 0);
        $limit = (int)$request->query('limit', 100);

        if (!ctype_digit((string)$from) || !ctype_digit((string)$to)) {
            return response()->json(['error' => 'Invalid parameters'], 400);
        }

        $records = Person::query()
            ->whereBetween('ari8mosEisagoghs', [(int)$from, (int)$to])
            ->orderBy('ari8mosEisagoghs')
            ->offset($offset)
            ->limit($limit)
            ->get();

        $data = $records->map(function ($p) {
            return [
                'ari8mosEisagoghs' => $p->ari8mosEisagoghs,
                'hmeromhnia_eis' => $p->hmeromhnia_eis ?: '-',
                'syggrafeas' => $p->syggrafeas ?: '-',
                'koha' => $p->koha ?: '-',
                'titlos' => $p->titlos ?: '-',
                'ekdoths' => $p->ekdoths ?: '-',
                'ekdosh' => $p->ekdosh ?: '-',
                'etosEkdoshs' => $p->etosEkdoshs ?: '-',
                'toposEkdoshs' => $p->toposEkdoshs ?: '-',
                'sxhma' => $p->sxhma ?: '-',
                'selides' => $p->selides ?: '-',
                'tomos' => $p->tomos ?: '-',
                'ISBN' => $p->ISBN ?: '-',
            ];
        });

        return response()->json([
            'records' => $data,
            'has_more' => $records->count() === $limit,
        ]);
    }

    private function personRules(): array
    {
        return [
            'hmeromhnia_eis' => ['nullable', 'string', 'max:200'],
            'syggrafeas' => ['nullable', 'string', 'max:200'],
            'koha' => ['nullable', 'string', 'max:200'],
            'titlos' => ['nullable', 'string', 'max:200'],
            'ekdoths' => ['nullable', 'string', 'max:200'],
            'ekdosh' => ['nullable', 'string', 'max:200'],
            'etosEkdoshs' => ['nullable', 'string', 'max:20'],
            'toposEkdoshs' => ['nullable', 'string', 'max:200'],
            'sxhma' => ['nullable', 'string', 'max:200'],
            'selides' => ['nullable', 'string', 'max:50'],
            'tomos' => ['nullable', 'string', 'max:50'],
            'troposPromPar' => ['nullable', 'string', 'max:200'],
            'ISBN' => ['nullable', 'string', 'max:50'],
            'sthlh1' => ['nullable', 'string', 'max:200'],
            'sthlh2' => ['nullable', 'string', 'max:200'],
        ];
    }

    private function nextIncomplete(): ?Person
    {
        return Person::query()->whereNull('syggrafeas')
            ->whereNull('koha')
            ->whereNull('titlos')
            ->whereNull('ekdoths')
            ->whereNull('ekdosh')
            ->whereNull('etosEkdoshs')
            ->whereNull('toposEkdoshs')
            ->whereNull('sxhma')
            ->whereNull('selides')
            ->whereNull('tomos')
            ->whereNull('ISBN')
            ->whereNull('sthlh1')
            ->whereNull('sthlh2')
            ->orderBy('ari8mosEisagoghs')
            ->first();
    }

    private function clearImportSession(): void
    {
        session()->forget([
            'duplicates',
            'potential_insertions',
            'new_records_count',
            'skipped_count',
            'upload_filename',
        ]);
    }
}
