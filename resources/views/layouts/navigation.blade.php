<nav>
    <div class="nav-row">
        <a href="{{ route('home') }}">🏠 Αρχική</a>
        <a href="{{ route('upload') }}">📊 Εισαγωγή Αρχείου Excel</a>
        <a href="{{ route('people.add') }}">📚 Νέα Εγγραφή Βιβλίου</a>
    </div>

    <div class="nav-row">
        <a href="{{ route('people.index') }}">📖 Όλες οι Εγγραφές</a>
        <a href="{{ route('people.incomplete') }}">🧩 Κενά Στοιχεία</a>

        @auth
        @if(auth()->user()->is_superuser)
            <a href="{{ route('activity-logs.index') }}">🛠 Logs</a>
            <a href="{{ route('admin.db-backup') }}">🛠 Database backup</a>
        @endif

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit">🚪 Αποσύνδεση</button>
            </form>
        @else
            <a href="{{ route('login') }}">🔐 Σύνδεση</a>
            <a href="{{ route('register') }}">📝 Εγγραφή</a>
        @endauth
    </div>
</nav>
