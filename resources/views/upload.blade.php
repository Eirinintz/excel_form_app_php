<form action="{{ route('people.upload') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <h2>Μεταφόρτωση αρχείου Excel</h2>
    <input type="file" name="file" accept=".xlsx,.xls" required>
    <button type="submit">Μεταφόρτωση</button>
</form>

