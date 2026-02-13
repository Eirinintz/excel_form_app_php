<x-app-layout>
    @auth
        <p>Γειά σου, {{ Auth::user()->name ?? Auth::user()->email }}!</p>
        <p style="margin-top:12px;">Χρησιμοποίησε το μενού για να εισάγεις Excel ή να δεις/επεξεργαστείς εγγραφές.</p>
    @else
        <p>Δεν είστε συνδεμένος/η.</p>
        <p style="margin-top:10px;">
            <a href="{{ route('login') }}" style="text-decoration:underline;">Σύνδεση</a> |
            <a href="{{ route('register') }}" style="text-decoration:underline;">Εγγραφή</a>
        </p>
    @endauth
</x-app-layout>
