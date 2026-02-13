<nav>
    <div class="nav-row">
        <a href="{{ route('home') }}">🏠 Αρχική</a>
        <a href="{{ route('upload') }}">📊 Εισαγωγή αρχείου Excel</a>
        <a href="{{ route('people.add') }}">📚 Νέα εγγραφή βιβλίου</a>
    </div>

    <div class="nav-row">
        <a href="{{ route('people.index') }}">📖 Όλες οι εγγραφές</a>
        <a href="{{ route('people.incomplete') }}">🧩 Ελλιπείς εγγραφές</a>

        @auth
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
