
<x-layout title="backup">


    <h1>Database Backup</h1>

    @if(session('status'))
        <div style="margin: 10px 0; padding: 10px; background-color: #d4edda; color: #155724; border-radius: 5px;">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('admin.db-backup.perform') }}">
        @csrf
        <button type="submit" style="background:#1f3c88; color:white; padding:10px 20px; border:none; border-radius:5px; cursor:pointer;">
            Backup Database
        </button>
    </form>
<br>
    

    


    <form action="{{ route('admin.export.csv') }}" method="GET">
    <label for="table">Select Table:</label>
    <select name="table" id="table" required>
        @foreach($tables as $table)
            <option value="{{ $table }}">{{ $table }}</option>
        @endforeach
    </select>

    <button type="submit" style="background:#816ef7; color:white; padding:10px 20px; border:none; border-radius:5px; cursor:pointer;">Export CSV</button>
    </form>




</x-layout>