<x-app-layout>
<style>
    /* Override base.html styles for this page */
    .page-wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            flex-direction: column;
            text-align: center;
            padding: 20px;
    }
    .content-box { max-width: 100%; padding: 20px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
    h1 { color: #1f2937; margin-bottom: 20px; font-size: 2rem; }
    table { width: 100%; border-collapse: collapse; margin-top: 20px; background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
    th, td { border: 1px solid #388038; padding: 12px 8px; text-align: left; font-size: 0.9rem; }
    th { background: linear-gradient(135deg, #4CAF50 0%, #45a049 100%); color: white; font-weight: 600; position: sticky; top: 0; z-index: 10; }
    tr:nth-child(even) { background-color: #f9fafb; }
    tr:hover { background-color: #f0f9ff; transition: background-color 0.2s ease; }
    .btn { padding: 6px 12px; margin: 2px; text-decoration: none; border-radius: 5px; display: inline-block; color: white; border: none; cursor: pointer; font-size: 0.85rem; font-weight: 500; transition: transform 0.2s ease, box-shadow 0.2s ease; }
    .btn-edit { background: linear-gradient(135deg, #34a747 0%, #19d266 100%); }
    .btn-delete { background: linear-gradient(135deg, #f44336 0%, #d32f2f 100%); }
    .btn:hover { transform: translateY(-2px); box-shadow: 0 4px 8px rgba(0,0,0,0.2); }
    .loading { display:none; color:#4CAF50; font-style: italic; margin-left:10px; font-weight:500; }
    #pagination, #paginationBottom { margin: 20px 0; text-align:center; }
    #pagination a, #paginationBottom a { display:inline-block; padding:8px 12px; margin:0 4px; background:#f5f0e6; color:#1f2937; text-decoration:none; border-radius:5px; border:2px solid #d6c9b8; font-weight:600; transition: all 0.2s ease; }
    #pagination a:hover, #paginationBottom a:hover { background:#e0d4bf; transform: translateY(-2px); }
    #pagination span, #paginationBottom span { padding:8px 12px; font-weight:600; color:#1f2937; }
    .search-section { margin:20px 0; padding:20px; background: rgba(249,249,249,0.95); border-radius:8px; box-shadow:0 2px 4px rgba(0,0,0,0.05); }
    #searchInput { padding:10px 15px; min-width:300px; font-size:1rem; border:2px solid #d6c9b8; border-radius:6px; transition:border-color 0.2s ease; }
    #searchCategory { padding:10px; border-radius:6px; border:2px solid #d6c9b8; background:white; font-size:1rem; cursor:pointer; }
    .btn-primary { background: linear-gradient(135deg, #FF9800 0%, #F57C00 100%); color:white; padding:8px 16px; border:none; border-radius:6px; cursor:pointer; font-weight:600; }
    @media (max-width: 768px) {
        table { font-size: 0.8rem; }
        th, td { padding: 8px 4px; }
        #searchInput { min-width: 100%; }
    }
</style>

<h1>Εισαγωγές βιβλίων</h1>

<a href="{{ route('people.incomplete') }}" class="btn btn-edit" style="padding: 10px 20px;">
    Κενά στοιχεία
</a>

@if (session('status'))
    <div style="padding: 10px; margin: 10px 0; border-radius: 5px; background: #d4edda;">
        {{ session('status') }}
    </div>
@endif

<span id="loadingIndicator" class="loading">Φόρτωση...</span>

<div class="search-section">
    <form method="get" id="searchForm" style="display:flex;gap:10px;flex-wrap:wrap;align-items:center;">
        <select name="search_category" id="searchCategory">
            <option value="all" {{ $search_category==='all' ? 'selected' : '' }}>Όλα τα πεδία</option>
            <option value="ari8mos" {{ $search_category==='ari8mos' ? 'selected' : '' }}>Αριθμός Εισαγωγής</option>
            <option value="hmeromhnia_eis" {{ $search_category==='hmeromhnia_eis' ? 'selected' : '' }}>Ημερομηνία Εισαγωγής</option>
            <option value="syggrafeas" {{ $search_category==='syggrafeas' ? 'selected' : '' }}>Συγγραφέας</option>
            <option value="titlos" {{ $search_category==='titlos' ? 'selected' : '' }}>Τίτλος</option>
            <option value="ekdoths" {{ $search_category==='ekdoths' ? 'selected' : '' }}>Εκδότης</option>
            <option value="ISBN" {{ $search_category==='ISBN' ? 'selected' : '' }}>ISBN</option>
        </select>

        <input type="text" name="search" value="{{ $search }}" placeholder="Αναζήτηση..." id="searchInput">

        <button type="submit" style="padding:10px 20px;background-color:#4CAF50;color:white;border:none;border-radius:5px;cursor:pointer;">Αναζήτηση</button>

        @if($search)
            <a href="{{ route('people.index') }}" style="padding:10px 20px;background-color:#f44336;color:white;border:none;border-radius:5px;text-decoration:none;display:inline-block;">Καθαρισμός</a>
        @endif
    </form>

    @if($search)
        <div style="margin-top:10px;padding:10px;background:#e3f2fd;border-left:4px solid #2196F3;border-radius:4px;">
            <strong>Αναζήτηση:</strong> "{{ $search }}" |
            <strong>σε:</strong> {{ $search_category }} |
            <strong>Αποτελέσματα:</strong> {{ $pageObj->total() }}
        </div>
    @endif
</div>

<form method="get" action="{{ route('print.range') }}" target="_blank" style="display:inline;">
    <input type="text" placeholder="από" name="from_num" id="print_from_num">
    <input type="text" placeholder="έως" name="to_num" id="print_to_num">
    <button type="submit" class="btn-primary" onclick="setPrintRange()">🖨️ Εκτύπωση Εύρους</button>
</form>

<div id="pagination">
    @if($pageObj->onFirstPage()===false)
        <a href="#" onclick="loadPage(1); return false;">« Πρώτο</a>
        <a href="#" onclick="loadPage({{ $pageObj->currentPage()-1 }}); return false;">Προηγούμενο</a>
    @endif
    <span>Σελίδα {{ $pageObj->currentPage() }} από {{ $pageObj->lastPage() }}</span>
    @if($pageObj->hasMorePages())
        <a href="#" onclick="loadPage({{ $pageObj->currentPage()+1 }}); return false;">Επόμενο</a>
        <a href="#" onclick="loadPage({{ $pageObj->lastPage() }}); return false;">Τελευταίο »</a>
    @endif
</div>

<table>
    <thead>
        <tr>
            <th>Αριθμός Εισαγωγής</th>
            <th>Ημερομηνία Εισαγωγής</th>
            <th>Συγγραφέας</th>
            <th>ΚΟΗΑ</th>
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
            <th>Επεξεργασία</th>
        </tr>
    </thead>
    <tbody id="tableBody">
        @include('partials.people_table_rows', ['pageObj' => $pageObj])
    </tbody>
</table>

<div id="paginationBottom"></div>

<form id="deleteForm" method="post" style="display:none;">
    @csrf
</form>

<script>
let searchTimeout;
const searchInput = document.getElementById('searchInput');
const searchCategory = document.getElementById('searchCategory');
const tableBody = document.getElementById('tableBody');
const loadingIndicator = document.getElementById('loadingIndicator');
const pagination = document.getElementById('pagination');
const paginationBottom = document.getElementById('paginationBottom');
const searchForm = document.getElementById('searchForm');

searchCategory.addEventListener('change', function() {
    const placeholders = {
        'all': 'Αναζήτηση σε όλα τα πεδία...',
        'ari8mos': 'Εισάγετε αριθμό εισαγωγής...',
        'titlos': 'Αναζήτηση τίτλου...',
        'syggrafeas': 'Αναζήτηση συγγραφέα...',
        'hmeromhnia_eis': 'Αναζήτηση ημερομηνίας εισαγωγής...',
        'ekdoths': 'Αναζήτηση εκδότη...',
        'ISBN': 'Αναζήτηση ISBN...'
    };
    searchInput.placeholder = placeholders[this.value] || 'Αναζήτηση...';
    searchInput.focus();
});

searchInput.addEventListener('input', function() {
    clearTimeout(searchTimeout);
    loadingIndicator.style.display = 'inline';
    searchTimeout = setTimeout(function() {
        performSearch();
    }, 300);
});

searchForm.addEventListener('submit', function(e) {
    e.preventDefault();
    performSearch();
});

function performSearch(page = 1) {
    const url = new URL("{{ route('people.index') }}", window.location.origin);
    const searchValue = searchInput.value;
    const categoryValue = searchCategory.value;

    if (searchValue) url.searchParams.set('search', searchValue);
    url.searchParams.set('search_category', categoryValue);
    url.searchParams.set('page', page);

    window.history.pushState({}, '', url);

    fetch(url, { headers: {'X-Requested-With':'XMLHttpRequest'} })
        .then(r => r.json())
        .then(data => {
            tableBody.innerHTML = data.html;
            updatePagination(data);
            loadingIndicator.style.display = 'none';
        })
        .catch(() => { loadingIndicator.style.display = 'none'; });
}

function loadPage(p){ performSearch(p); }

function updatePagination(data){
    let html = '';
    if (data.has_previous){
        html += `<a href="#" onclick="loadPage(1); return false;">« Πρώτο</a> `;
        html += `<a href="#" onclick="loadPage(${data.current_page-1}); return false;">Προηγούμενο</a> `;
    }
    html += `<span>Σελίδα ${data.current_page} από ${data.total_pages}</span> `;
    if (data.has_next){
        html += `<a href="#" onclick="loadPage(${data.current_page+1}); return false;">Επόμενο</a> `;
        html += `<a href="#" onclick="loadPage(${data.total_pages}); return false;">Τελευταίο »</a>`;
    }
    pagination.innerHTML = html;
    paginationBottom.innerHTML = html;
}

function confirmDelete(ari8mos, title){
    if (confirm(`Είστε σίγουρος/η ότι θέλετε να διαγράψετε αυτή την εγγραφή; #${ari8mos}?\n\nΤίτλος: ${title}\n\nΑυτή η ενέργεια δεν μπορεί να αναιρεθεί!`)){
        const form = document.getElementById('deleteForm');
        form.action = `{{ url('/people/delete') }}/${ari8mos}`;
        form.submit();
    }
}

function setPrintRange(){
    const from = document.getElementById('print_from_num').value;
    const to = document.getElementById('print_to_num').value;
    document.getElementById('print_from_num').value = from;
    document.getElementById('print_to_num').value = to;
}
</script>
</x-app-layout>
