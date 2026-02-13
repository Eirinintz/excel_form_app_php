<x-app-layout>
<style>
.page-wrapper { max-width: 1400px; margin: 0 auto; padding: 20px; }
.page-title { text-align: center; margin-bottom: 30px; }
.info-box, .insertion-box { max-width: 900px; margin: 20px auto; padding: 15px 20px; border-radius: 8px; text-align: center; }
.info-box { background: #fff3cd; border-left: 5px solid #ffc107; }
.insertion-box { background: #e6f4ea; border-left: 5px solid #28a745; }
.action-buttons { text-align: center; margin: 30px 0; }
.btn { padding: 12px 22px; margin: 8px; border: none; border-radius: 6px; cursor: pointer; font-size: 15px; font-weight: bold; }
.btn-replace { background:#28a745; color:#fff; }
.btn-skip { background:#dc3545; color:#fff; }
.btn-home { background:#17a2b8; color:#fff; }
.btn-select-all { background:#f0ad4e; color:#fff; }
.btn:hover { opacity:0.9; }
.card { background:#fff; border-radius: 10px; padding: 25px; margin: 40px 0; box-shadow: 0 4px 12px rgba(0,0,0,0.08); text-align:left; }
.table-wrapper { overflow-x: auto; }
table { width: 100%; border-collapse: collapse; margin: 15px 0; }
th, td { border: 1px solid #ddd; padding: 8px; font-size: 14px; }
th { text-align: center; color: #fff; }
.excel-table th { background:#1f3c88; }
.database-table th { background:#8a1f1f; }
.insertion-table th { background:#2d7a2d; }
.record-selector { text-align: center; margin-bottom: 15px; }
.record-selector input { transform: scale(1.4); margin-right: 8px; }
</style>

<div class="page-wrapper">
    <h2 class="page-title">📑 Επίλυση Διπλότυπων & Κενών Εγγραφών</h2>

    @if($duplicate_count > 0)
        <div class="info-box">
            <strong>⚠️ Βρέθηκαν {{ $duplicate_count }} διπλότυπες εγγραφές</strong><br>
            Επιλέξτε ποιες θα αντικατασταθούν από τα δεδομένα Excel.
        </div>
    @endif

    @if($insertion_count > 0)
        <div class="insertion-box">
            <strong>✨ {{ $insertion_count }} κενές εγγραφές μπορούν να συμπληρωθούν</strong>
        </div>
    @endif

    <form method="post" action="{{ route('duplicates.replace') }}">
        @csrf

        <div class="action-buttons">
            <button type="button" class="btn btn-select-all" onclick="selectAllDuplicates()">☑️ Όλα τα Διπλότυπα</button>
            <button type="button" class="btn btn-select-all" onclick="selectAllInsertions()">☑️ Όλες οι Κενές</button>

            <button type="submit" class="btn btn-replace" onclick="return confirm('Η ενέργεια δεν αναιρείται. Συνέχεια;')">
                ✅ Αντικατάσταση
            </button>

            <button type="button" class="btn btn-skip" onclick="document.getElementById('skipForm').submit();">
                ⏭️ Παράλειψη
            </button>

            <a href="{{ route('home') }}"><button type="button" class="btn btn-home">🏠 Αρχική</button></a>
        </div>

        @foreach($duplicates as $i => $dup)
            <div class="card">
                <div class="record-selector">
                    <input type="checkbox" name="duplicate_ids[]" value="{{ $dup['left']['ari8mos'] ?? '' }}" class="duplicate-checkbox">
                    <strong>Αντικατάσταση εγγραφής</strong>
                </div>

                <h3>Διπλότυπο #{{ $i+1 }} — Αρ. Εισαγωγής {{ $dup['left']['ari8mos'] ?? '' }}</h3>

                <div class="table-wrapper">
                    <table class="excel-table">
                        <thead><tr><th colspan="14">Excel (Νέα Δεδομένα)</th></tr></thead>
                        <tbody><tr>
                            <td>{{ $dup['right']['ari8mos'] ?? '-' }}</td>
                            <td>{{ $dup['right']['hmeromhnia_eis'] ?? '-' }}</td>
                            <td>{{ $dup['right']['syggrafeas'] ?? '-' }}</td>
                            <td>{{ $dup['right']['koha'] ?? '-' }}</td>
                            <td>{{ $dup['right']['titlos'] ?? '-' }}</td>
                            <td>{{ $dup['right']['ekdoths'] ?? '-' }}</td>
                            <td>{{ $dup['right']['ekdosh'] ?? '-' }}</td>
                            <td>{{ $dup['right']['etosEkdoshs'] ?? '-' }}</td>
                            <td>{{ $dup['right']['toposEkdoshs'] ?? '-' }}</td>
                            <td>{{ $dup['right']['sxhma'] ?? '-' }}</td>
                            <td>{{ $dup['right']['selides'] ?? '-' }}</td>
                            <td>{{ $dup['right']['tomos'] ?? '-' }}</td>
                            <td>{{ $dup['right']['troposPromPar'] ?? '-' }}</td>
                            <td>{{ $dup['right']['ISBN'] ?? '-' }}</td>
                        </tr></tbody>
                    </table>
                </div>

                <div class="table-wrapper">
                    <table class="database-table">
                        <thead><tr><th colspan="14">Database (Υπάρχοντα)</th></tr></thead>
                        <tbody><tr>
                            <td>{{ $dup['left']['ari8mos'] ?? '-' }}</td>
                            <td>{{ $dup['left']['hmeromhnia_eis'] ?? '-' }}</td>
                            <td>{{ $dup['left']['syggrafeas'] ?? '-' }}</td>
                            <td>{{ $dup['left']['koha'] ?? '-' }}</td>
                            <td>{{ $dup['left']['titlos'] ?? '-' }}</td>
                            <td>{{ $dup['left']['ekdoths'] ?? '-' }}</td>
                            <td>{{ $dup['left']['ekdosh'] ?? '-' }}</td>
                            <td>{{ $dup['left']['etosEkdoshs'] ?? '-' }}</td>
                            <td>{{ $dup['left']['toposEkdoshs'] ?? '-' }}</td>
                            <td>{{ $dup['left']['sxhma'] ?? '-' }}</td>
                            <td>{{ $dup['left']['selides'] ?? '-' }}</td>
                            <td>{{ $dup['left']['tomos'] ?? '-' }}</td>
                            <td>{{ $dup['left']['troposPromPar'] ?? '-' }}</td>
                            <td>{{ $dup['left']['ISBN'] ?? '-' }}</td>
                        </tr></tbody>
                    </table>
                </div>
            </div>
        @endforeach

        @if(!empty($potential_insertions))
            <h2 style="color:#2d7a2d;">✨ Κενές Εγγραφές για Συμπλήρωση ({{ $insertion_count }})</h2>

            @foreach($potential_insertions as $j => $ins)
                <div class="record-selector">
                    <input type="checkbox" name="insertion_ids[]" value="{{ $ins['ari8mos'] ?? '' }}" class="insertion-checkbox" id="ins_{{ $ins['ari8mos'] ?? $j }}">
                    <label for="ins_{{ $ins['ari8mos'] ?? $j }}"><strong>Συμπλήρωση αυτής της κενής εγγραφής με δεδομένα από το Excel</strong></label>
                </div>

                <h3 style="margin-top:30px;color:#2d7a2d;">Κενή Εγγραφή #{{ $j+1 }} - Αρ. Εισαγωγής: {{ $ins['ari8mos'] ?? '' }}</h3>

                <h4 style="color:#8a1f1f;">Database Data (Κενή Εγγραφή)</h4>
                <table class="database-table">
                    <thead><tr><th>Αρ. Εισαγωγής</th><th>Ημ/νία Εισαγωγής</th></tr></thead>
                    <tbody><tr>
                        <td>{{ $ins['database']['ari8mos'] ?? '-' }}</td>
                        <td>{{ $ins['database']['hmeromhnia_eis'] ?? '-' }}</td>
                    </tr></tbody>
                </table>

                <h4 style="color:#2d7a2d;">Excel Data (Νέα Δεδομένα για Συμπλήρωση)</h4>
                <table class="insertion-table">
                    <thead><tr>
                        <th>Αρ. Εισαγωγής</th><th>Ημ/νία Εισαγωγής</th><th>Συγγραφέας</th><th>KOHA</th><th>Τίτλος</th><th>Εκδότης</th><th>Έκδοση</th><th>Έτος Έκδοσης</th><th>Τόπος Έκδοσης</th><th>Σχήμα</th><th>Σελίδες</th><th>Τόμος</th><th>Τρόπος Προμήθειας</th><th>ISBN</th>
                    </tr></thead>
                    <tbody><tr>
                        <td>{{ $ins['excel']['ari8mosEisagoghs'] ?? '-' }}</td>
                        <td>{{ $ins['excel']['hmeromhnia_eis'] ?? '-' }}</td>
                        <td>{{ $ins['excel']['syggrafeas'] ?? '-' }}</td>
                        <td>{{ $ins['excel']['koha'] ?? '-' }}</td>
                        <td>{{ $ins['excel']['titlos'] ?? '-' }}</td>
                        <td>{{ $ins['excel']['ekdoths'] ?? '-' }}</td>
                        <td>{{ $ins['excel']['ekdosh'] ?? '-' }}</td>
                        <td>{{ $ins['excel']['etosEkdoshs'] ?? '-' }}</td>
                        <td>{{ $ins['excel']['toposEkdoshs'] ?? '-' }}</td>
                        <td>{{ $ins['excel']['sxhma'] ?? '-' }}</td>
                        <td>{{ $ins['excel']['selides'] ?? '-' }}</td>
                        <td>{{ $ins['excel']['tomos'] ?? '-' }}</td>
                        <td>{{ $ins['excel']['troposPromPar'] ?? '-' }}</td>
                        <td>{{ $ins['excel']['ISBN'] ?? '-' }}</td>
                    </tr></tbody>
                </table>

                <hr style="margin:40px 0;border:1px solid #ccc;">
            @endforeach
        @endif
    </form>

    <form method="post" action="{{ route('duplicates.skip') }}" id="skipForm" style="display:none;">
        @csrf
    </form>
</div>

<script>
function selectAllDuplicates(){
    const cbs = document.querySelectorAll('.duplicate-checkbox');
    const allChecked = Array.from(cbs).every(cb => cb.checked);
    cbs.forEach(cb => cb.checked = !allChecked);
}
function selectAllInsertions(){
    const cbs = document.querySelectorAll('.insertion-checkbox');
    const allChecked = Array.from(cbs).every(cb => cb.checked);
    cbs.forEach(cb => cb.checked = !allChecked);
}
</script>
</x-app-layout>
