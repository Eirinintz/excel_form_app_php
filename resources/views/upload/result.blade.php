<x-app-layout>
<div class="container" style="
    font-family: Arial, sans-serif;
    background-color: #f5f0e6;
    padding: 25px;
    border: 2px solid #d6c9b8;
    border-radius: 8px;
    box-shadow: 0 0 12px rgba(0,0,0,0.15);
    width: 450px;
    margin: 20px auto;
    text-align: center;
">
    <h2 style="margin-bottom:20px;">Αποτελέσματα Μεταφόρτωσης</h2>

    <p><strong>Νέες Εισαγωγές:</strong> {{ $added_count }}</p>

    <p><strong>Ενημερωμένες Εγγραφές:</strong> {{ $updated_count ?? 0 }}</p>

    <p><strong>Διπλότυπα:</strong> {{ $duplicate_count ?? 0 }}</p>

    <p><strong>Παραλείφθηκαν:</strong> {{ $skipped_count }}</p>

    @if(($duplicate_count ?? 0) > 0)
        <a href="{{ route('duplicates.resolve') }}">
            <button style="padding:10px 20px;background-color:#f5f0e6;color:#5cb85c;border:none;border-radius:5px;cursor:pointer;margin-top:15px;width:100%;">
                Προβολή & Επίλυση Διπλότυπων
            </button>
        </a>
    @endif

    <br><br>

    <a href="{{ route('upload') }}">
        <button style="padding:10px 20px;background-color:#5cb85c;color:white;border:none;border-radius:5px;cursor:pointer;width:100%;">
            Νέα Μεταφόρτωση Excel
        </button>
    </a>

    <br><br>

    <a href="{{ route('people.index') }}">
        <button style="padding:10px 20px;background-color:#0275d8;color:white;border:none;border-radius:5px;cursor:pointer;width:100%;">
            Προβολή Όλων των Εγγραφών (Σύνολο: {{ $total_records }})
        </button>
    </a>
</div>
</x-app-layout>
