<!DOCTYPE html>
<html lang="el">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Εκτύπωση εγγραφών {{ $from_num }} - {{ $to_num }}</title>
    <style>
        @media print {
            .no-print { display: none; }
            body { margin: 0; padding: 20px; }
        }
        body { font-family: Arial, sans-serif; font-size: 12px; }
        h1 { text-align: center; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; }
        th { background-color: #f0f0f0; font-weight: bold; }
        .actions { text-align: center; margin: 20px 0; padding: 20px; }
        .btn { padding: 10px 20px; margin: 0 10px; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; text-decoration: none; display: inline-block; }
        .btn-print { background-color: #4CAF50; color: white; }
        .btn-back { background-color: #2196F3; color: white; }
        .progress-bar { width: 70%; margin: 10px auto; border:1px solid #ccc; border-radius:6px; overflow:hidden; }
        .progress-fill { width:0%; padding:6px 0; text-align:center; background:#4CAF50; color:white; font-weight:bold; }
        @page { size: landscape; margin: 1cm; }
    </style>
</head>
<body>
<div class="actions no-print">
    <button onclick="printTable()" class="btn btn-print" id="printBtn" disabled>Εκτύπωση</button>
    <a href="{{ route('people.index') }}" class="btn btn-back">Επιστροφή</a>
</div>

<div id="loadingIndicator" class="no-print">
    <h2>Φόρτωση δεδομένων...</h2>
    <div class="progress-bar">
        <div class="progress-fill" id="progressFill">0%</div>
    </div>
    <p id="loadingText">Φόρτωση εγγραφών: <span id="loadedCount">0</span> / {{ $total_count }}</p>
</div>

<h1>Εγγραφές {{ $from_num }} - {{ $to_num }}</h1>

<table id="booksTable">
    <thead>
        <tr>
            <th>Αρ. Εισαγωγής</th>
            <th>Ημ/νία Εισαγωγής</th>
            <th>Συγγραφέας</th>
            
            <th>Τίτλος</th>
            <th>Εκδότης</th>
            <th>Έκδοση</th>
            <th>Έτος Έκδοσης</th>
            <th>Τόπος Έκδοσης</th>
            <th>Σχήμα</th>
            <th>Σελίδες</th>
            <th>Τόμος</th>
            <th>Τρόπος Προμηθείας/Παραλαβής</th>
            <th>ISBN</th>
        </tr>
    </thead>
    <tbody id="tableBody"></tbody>
</table>

<div class="no-print" style="text-align:center;margin-top:30px;">
    <p>Σύνολο εγγραφών: <strong>{{ $total_count }}</strong></p>
</div>

<script>
const FROM_NUM = {{ $from_num }};
const TO_NUM = {{ $to_num }};
const TOTAL_COUNT = {{ $total_count }};
const BATCH_SIZE = 500;

let loadedCount = 0;
let allRecordsLoaded = false;

async function loadBatch(offset){
    const url = `{{ route('print.range.data') }}?from_num=${FROM_NUM}&to_num=${TO_NUM}&offset=${offset}&limit=${BATCH_SIZE}`;
    const response = await fetch(url);
    if (!response.ok) throw new Error('Failed');
    return await response.json();
}

function renderRecords(records){
    const tbody = document.getElementById('tableBody');
    const fragment = document.createDocumentFragment();
    records.forEach(record => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${record.ari8mosEisagoghs}</td>
            <td>${record.hmeromhnia_eis}</td>
            <td>${record.syggrafeas}</td>
            
            <td>${record.titlos}</td>
            <td>${record.ekdoths}</td>
            <td>${record.ekdosh}</td>
            <td>${record.etosEkdoshs}</td>
            <td>${record.toposEkdoshs}</td>
            <td>${record.sxhma}</td>
            <td>${record.selides}</td>
            <td>${record.tomos}</td>
            <td>${record.troposPromPar}</td>
            <td>${record.ISBN}</td>
        `;
        fragment.appendChild(row);
    });
    tbody.appendChild(fragment);
}

function updateProgress(){
    const percentage = Math.round((loadedCount / TOTAL_COUNT) * 100);
    const fill = document.getElementById('progressFill');
    fill.style.width = percentage + '%';
    fill.textContent = percentage + '%';
    document.getElementById('loadedCount').textContent = loadedCount;
}

async function loadAllRecords(){
    let offset = 0;
    while (offset < TOTAL_COUNT){
        let data;
        try { data = await loadBatch(offset); }
        catch(e){
            alert('Σφάλμα κατά τη φόρτωση δεδομένων. Παρακαλώ δοκιμάστε ξανά.');
            break;
        }
        if (!data || !data.records) break;

        renderRecords(data.records);
        loadedCount += data.records.length;
        updateProgress();
        offset += BATCH_SIZE;

        await new Promise(r => setTimeout(r, 10));
        if (!data.has_more) break;
    }
    allRecordsLoaded = true;
    document.getElementById('loadingIndicator').style.display = 'none';
    document.getElementById('printBtn').disabled = false;
}

function printTable(){
    if (!allRecordsLoaded){
        alert('Παρακαλώ περιμένετε να φορτώσουν όλες οι εγγραφές πριν την εκτύπωση.');
        return;
    }
    window.print();
}

window.addEventListener('DOMContentLoaded', loadAllRecords);

window.addEventListener('beforeunload', function(e){
    if (!allRecordsLoaded){
        e.preventDefault();
        e.returnValue = '';
        return 'Η φόρτωση δεν έχει ολοκληρωθεί. Είστε σίγουροι ότι θέλετε να φύγετε;';
    }
});
</script>
</body>
</html>
