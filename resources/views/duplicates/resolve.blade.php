<x-app-layout>
<style>
.page-wrapper1 { max-width: 1400px; margin: 0 auto; padding: 20px; }
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
.btn-select-all.active {
    background-color: #f59e0b; /* same orange */
    box-shadow: inset 0 0 0 2px rgba(255,255,255,0.4);
    font-weight: bold;
}
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

<div class="page-wrapper1">
    
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

        <!-- 🔴 REQUIRED: JS inserts hidden inputs here -->
        <div id="hidden-inputs"></div>

        <div class="action-buttons">
            <button type="button" class="btn btn-select-all" onclick="selectAllDuplicates()">☑️ Όλα τα Διπλότυπα</button>
            <button type="button" class="btn btn-select-all" onclick="selectAllInsertions()">☑️ Όλες οι Κενές</button>

           

            <button type="submit"
                class="btn btn-replace"
                onclick="return prepareSubmit()">
                 ✅ Αντικατάσταση
            </button>

            <button type="button" class="btn btn-skip" onclick="document.getElementById('skipForm').submit();">
                ⏭️ Παράλειψη
            </button>

            
        </div>

        @foreach($duplicates as $i => $dup)
            <div class="card">
                <div class="record-selector">
                    <input type="checkbox"
                    name="duplicate_ids[]"
                    
                   value="{{ $dup['ari8mos'] }}"
                    class="duplicate-checkbox">
                    <strong>Αντικατάσταση εγγραφής</strong>
                </div>

                <h3>Διπλότυπο #{{ $i+1 }} — Αρ. Εισαγωγής {{ $dup['ari8mos'] }}</h3>

                <div class="table-wrapper">
                    <table class="excel-table">
                        <thead><tr><th colspan="14">Excel (Νέα Δεδομένα)</th></tr></thead>
                        <tbody><tr>
                            <td>{{ $dup['excel']['ari8mosEisagoghs'] ?? '-' }}</td>
                            <td>{{ $dup['excel']['hmeromhnia_eis'] ?? '-' }}</td>
                            <td>{{ $dup['excel']['syggrafeas'] ?? '-' }}</td>
                            <td>{{ $dup['excel']['koha'] ?? '-' }}</td>
                            <td>{{ $dup['excel']['titlos'] ?? '-' }}</td>
                            <td>{{ $dup['excel']['ekdoths'] ?? '-' }}</td>
                            <td>{{ $dup['excel']['ekdosh'] ?? '-' }}</td>
                            <td>{{ $dup['excel']['etosEkdoshs'] ?? '-' }}</td>
                            <td>{{ $dup['excel']['toposEkdoshs'] ?? '-' }}</td>
                            <td>{{ $dup['excel']['sxhma'] ?? '-' }}</td>
                            <td>{{ $dup['excel']['selides'] ?? '-' }}</td>
                            <td>{{ $dup['excel']['tomos'] ?? '-' }}</td>
                            <td>{{ $dup['excel']['troposPromPar'] ?? '-' }}</td>
                            <td>{{ $dup['excel']['ISBN'] ?? '-' }}</td>
                        </tr></tbody>
                    </table>
                </div>

                <div class="table-wrapper">
                    <table class="database-table">
                        <thead><tr><th colspan="14">Database (Υπάρχοντα)</th></tr></thead>
                        <tbody>
                        <tr>
                            <td>{{ $dup['database']['ari8mosEisagoghs'] ?? '-' }}</td>
                            <td>{{ $dup['database']['hmeromhnia_eis'] ?? '-' }}</td>
                            <td>{{ $dup['database']['syggrafeas'] ?? '-' }}</td>
                            <td>{{ $dup['database']['koha'] ?? '-' }}</td>
                            <td>{{ $dup['database']['titlos'] ?? '-' }}</td>
                            <td>{{ $dup['database']['ekdoths'] ?? '-' }}</td>
                            <td>{{ $dup['database']['ekdosh'] ?? '-' }}</td>
                            <td>{{ $dup['database']['etosEkdoshs'] ?? '-' }}</td>
                            <td>{{ $dup['database']['toposEkdoshs'] ?? '-' }}</td>
                            <td>{{ $dup['database']['sxhma'] ?? '-' }}</td>
                            <td>{{ $dup['database']['selides'] ?? '-' }}</td>
                            <td>{{ $dup['database']['tomos'] ?? '-' }}</td>
                            <td>{{ $dup['database']['troposPromPar'] ?? '-' }}</td>
                            <td>{{ $dup['database']['ISBN'] ?? '-' }}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        @endforeach

        @if(!empty($potential_insertions))
            <h2 style="color:#2d7a2d;">✨ Κενές Εγγραφές για Συμπλήρωση ({{ $insertion_count }})</h2>

            @foreach($potential_insertions as $j => $ins)
            <div class="card insertion-card">

                <div class="record-selector">
                    <input type="checkbox"
                        name="insertion_ids[]"
                        value="{{ $ins['ari8mos'] }}"
                        class="insertion-checkbox">
                    <strong>Συμπλήρωση κενής εγγραφής</strong>
                </div>

                <h3 style="color:#2d7a2d;">
                    Κενή Εγγραφή #{{ $j+1 }} — Αρ. Εισαγωγής {{ $ins['ari8mos'] }}
                </h3>

                {{-- Excel --}}
                <div class="table-wrapper">
                    <table class="excel-table">
                        <thead>
                            <tr><th colspan="14">Excel (Νέα Δεδομένα)</th></tr>
                        </thead>
                        <tbody>
                        <tr>
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
                        </tr>
                        </tbody>
                    </table>
                </div>

                {{-- Database (empty record) --}}
                <div class="table-wrapper">
                    <table class="database-table empty-database">
                        <thead>
                            <tr><th colspan="14">Database (Κενή Εγγραφή)</th></tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>{{ $ins['database']['ari8mosEisagoghs'] ?? '-' }}</td>
                            <td>{{ $ins['database']['hmeromhnia_eis'] ?? '-' }}</td>
                            <td>{{ $ins['database']['syggrafeas'] ?? '-' }}</td>
                            <td>{{ $ins['database']['koha'] ?? '-' }}</td>
                            <td>{{ $ins['database']['titlos'] ?? '-' }}</td>
                            <td>{{ $ins['database']['ekdoths'] ?? '-' }}</td>
                            <td>{{ $ins['database']['ekdosh'] ?? '-' }}</td>
                            <td>{{ $ins['database']['etosEkdoshs'] ?? '-' }}</td>
                            <td>{{ $ins['database']['toposEkdoshs'] ?? '-' }}</td>
                            <td>{{ $ins['database']['sxhma'] ?? '-' }}</td>
                            <td>{{ $ins['database']['selides'] ?? '-' }}</td>
                            <td>{{ $ins['database']['tomos'] ?? '-' }}</td>
                            <td>{{ $ins['database']['troposPromPar'] ?? '-' }}</td>
                            <td>{{ $ins['database']['ISBN'] ?? '-' }}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>

            </div>
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

<script>
function prepareSubmit() {
    const container = document.getElementById('hidden-inputs');
    container.innerHTML = '';

    document.querySelectorAll('.duplicate-checkbox:checked').forEach(cb => {
        const input = document.createElement('input');
        input.type  = 'hidden';
        input.name  = 'duplicate_ids[]';
        input.value = cb.value;
        container.appendChild(input);
    });

    document.querySelectorAll('.insertion-checkbox:checked').forEach(cb => {
        const input = document.createElement('input');
        input.type  = 'hidden';
        input.name  = 'insertion_ids[]';
        input.value = cb.value;
        container.appendChild(input);
    });

    if (document.getElementById('replace_all_duplicates')?.checked) {
    const input = document.createElement('input');
    input.type = 'hidden';
    input.name = 'replace_all_duplicates';
    input.value = '1';
    container.appendChild(input);
    }

    if (
        container.querySelectorAll('input').length === 0 &&
        !confirm('Δεν έχετε επιλέξει εγγραφές. Συνέχεια;')
    ) {
        return false;
    }

    return confirm('Η ενέργεια δεν αναιρείται. Συνέχεια;');
}
</script>

<script>
function toggleReplaceAll() {
    const checkbox = document.getElementById('replace_all_duplicates');
    const btn = document.getElementById('replaceAllBtn');

    checkbox.checked = !checkbox.checked;

    if (checkbox.checked) {
        btn.innerHTML = '☑️ Αντικατάσταση όλων';
        btn.classList.add('active');
    } else {
        btn.innerHTML = '⬜ Αντικατάσταση όλων';
        btn.classList.remove('active');
    }
}
</script>

</x-app-layout>
