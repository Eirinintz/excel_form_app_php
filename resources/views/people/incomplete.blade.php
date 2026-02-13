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
    .content-box {
        max-width: 100%; /* Full width for table */
        padding: 20px;
       
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    h1 {
        color: #1f2937;
        margin-bottom: 20px;
        font-size: 2rem;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
        background: white;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    th, td {
        border: 1px solid #388038;
        padding: 12px 8px;
        text-align: left;
        font-size: 0.9rem;
    }

    th {
        background: linear-gradient(135deg, #4CAF50 0%, #45a049 100%);
        color: white;
        font-weight: 600;
        position: sticky;
        top: 0;
        z-index: 10;
    }

    tr:nth-child(even) {
        background-color: #f9fafb;
    }

    tr:hover {
        background-color: #f0f9ff;
        transition: background-color 0.2s ease;
    }

    .btn {
        padding: 6px 12px;
        margin: 2px;
        text-decoration: none;
        border-radius: 5px;
        display: inline-block;
        color: white;
        border: none;
        cursor: pointer;
        font-size: 0.85rem;
        font-weight: 500;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .btn-primary {
        background: linear-gradient(135deg, #FF9800 0%, #F57C00 100%);
        color: white;
        padding: 8px 16px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-weight: 600;
        transition: all 0.2s ease;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(255, 152, 0, 0.3);
    }

    .null-field {
        color: #dc3545;
        font-weight: 700;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        table {
            font-size: 0.8rem;
        }
        th, td {
            padding: 8px 4px;
        }
    }
</style>


<h1>Incomplete Records</h1>

<div class="stats">
    <h2>Στατιστικά</h2>
    <p><strong>Συνολικά Στοιχεία:</strong> {{ $total_records }}</p>
    <p><strong>Ελλιπής Στοιχεία:</strong> {{ $count }}</p>
</div>

@if($first_incomplete)
    <div style="margin: 20px 0;">
        <a href="{{ route('people.add', ['ari8mos' => $first_incomplete->ari8mosEisagoghs]) }}" 
           class="btn btn-primary" 
           style="background: #4CAF50; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block;">
             Συμπλήρωσε το πρώτο στοιχείο (ID: {{ $first_incomplete->ari8mosEisagoghs }})
        </a>
    </div>
@else
    <div style="margin: 20px 0; padding: 10px; background: #d4edda; border-radius: 5px;">
        ✅ All records are complete!
    </div>
@endif


<h2>Sample Records (First 100)</h2>
<table>
    <thead>
        <tr>
            <th>Αριθμός</th>
            <th>Συγγραφέας</th>
            <th>KOHA</th>
            <th>Τίτλος</th>
            <th>Εκδότης</th>
            <th>Έτος</th>
            <th>ISBN</th>
        </tr>
    </thead>
    <tbody>
        @forelse($records as $record)
        <tr>
            <td>{{ $record->ari8mosEisagoghs }}</td>
            <td>@if($record->syggrafeas){{ $record->syggrafeas }}@else<span class="null-field">NULL</span>@endif</td>
            <td>@if($record->koha){{ $record->koha }}@else<span class="null-field">NULL</span>@endif</td>
            <td>@if($record->titlos){{ $record->titlos }}@else<span class="null-field">NULL</span>@endif</td>
            <td>@if($record->ekdoths){{ $record->ekdoths }}@else<span class="null-field">NULL</span>@endif</td>
            <td>@if($record->etosEkdoshs){{ $record->etosEkdoshs }}@else<span class="null-field">NULL</span>@endif</td>
            <td>@if($record->ISBN){{ $record->ISBN }}@else<span class="null-field">NULL</span>@endif</td>
        </tr>
        @empty
        <tr>
            <td colspan="7" style="text-align: center;">No incomplete records found!</td>
        </tr>
        @endforelse
    </tbody>
</table>

</x-app-layout>
