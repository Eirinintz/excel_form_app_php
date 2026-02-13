@extends('layouts.app')

@section('title', 'Εγγραφή Προσωπικού')

@section('content')

<div class="container" style="
    font-family: Arial, sans-serif;
    background-color:#f5f0e6;
    border: 2px solid #d6c9b8;
    border-radius:8px;
    padding:25px;
    width:350px;
    margin:5px auto;
    box-shadow:0 4px 12px rgba(0,0,0,0.1);
">

    <h2 style="text-align:center; margin-bottom:20px; font-weight:bold;">
        Εγγραφή Προσωπικού Βιβλιοθήκης
    </h2>

    <form method="POST" action="{{ route('register') }}" id="signupForm" style="display:flex; flex-direction:column;">
        @csrf

        <!-- Username -->
        <label for="id_username">Όνομα χρήστη:</label>
        <input type="text" id="id_username" name="name" value="{{ old('name') }}">
        <p id="usernameError" style="display:none; color:red; font-size:0.9em;"></p>
        @error('name')
            <p style="color:red; font-size:0.9em;">{{ $message }}</p>
        @enderror

        <!-- Email -->
        <label for="id_email">Email:</label>
        <input type="email" id="id_email" name="email" value="{{ old('email') }}">
        <p id="emailError" style="display:none; color:red; font-size:0.9em;"></p>
        @error('email')
            <p style="color:red; font-size:0.9em;">{{ $message }}</p>
        @enderror

        <!-- Password -->
        <label for="id_password1">Κωδικός πρόσβασης:</label>
        <input type="password" id="id_password1" name="password">
        <p id="passwordError" style="display:none; color:red; font-size:0.9em;"></p>
        @error('password')
            <p style="color:red; font-size:0.9em;">{{ $message }}</p>
        @enderror

        <!-- Confirm Password -->
        <label for="id_password2">Επιβεβαίωση κωδικού πρόσβασης:</label>
        <input type="password" id="id_password2" name="password_confirmation">
        <p id="confirmError" style="display:none; color:red; font-size:0.9em;"></p>

        <button type="submit" style="
            padding:10px;
            background-color:#c9b18f;
            border:2px solid #a89377;
            border-radius:8px;
            color:white;
            font-weight:700;
            cursor:pointer;
            margin-top:15px;
            transition: transform 0.2s ease, background 0.2s ease;
        ">
            Εγγραφή
        </button>
    </form>

    <p style="margin-top:20px;font-size:0.8em;">
        ❓ Αν αντιμετωπίσετε οποιοδήποτε πρόβλημα κατά την εγγραφή, επικοινωνήστε με τον διαχειριστή.
    </p>
</div>

<script>
    const usernameInput = document.getElementById('id_username');
    const emailInput = document.getElementById('id_email');
    const passwordInput = document.getElementById('id_password1');
    const confirmInput = document.getElementById('id_password2');

    const usernameError = document.getElementById('usernameError');
    const emailError = document.getElementById('emailError');
    const passwordError = document.getElementById('passwordError');
    const confirmError = document.getElementById('confirmError');

    const form = document.getElementById('signupForm');

    form.addEventListener('submit', function(event) {
        let valid = true;

        // Έλεγχος ονόματος χρήστη
        const usernameRegex = /^[A-Za-z0-9@.+-_]+$/;
        if (!usernameRegex.test(usernameInput.value)) {
            usernameError.textContent =
                '❌ Μη έγκυρο όνομα χρήστη. Χρησιμοποιήστε λατινικά γράμματα, αριθμούς ή τα σύμβολα @ . + - _';
            usernameError.style.display = 'block';
            valid = false;
        } else {
            usernameError.style.display = 'none';
        }

        // Έλεγχος email
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(emailInput.value)) {
            emailError.textContent =
                '❌ Παρακαλώ εισάγετε έγκυρη διεύθυνση ηλεκτρονικού ταχυδρομείου.';
            emailError.style.display = 'block';
            valid = false;
        } else {
            emailError.style.display = 'none';
        }

        // Έλεγχος κωδικού πρόσβασης
        const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[,-.@$!%*?&]).{8,}$/;

        if (!passwordRegex.test(passwordInput.value)) {
            passwordError.textContent =
                '❌ Ο κωδικός πρέπει να έχει τουλάχιστον 8 χαρακτήρες, ένα κεφαλαίο γράμμα, ένα μικρό, έναν αριθμό και ένα ειδικό σύμβολο.';
            passwordError.style.display = 'block';
            valid = false;
        } else {
            passwordError.style.display = 'none';
        }

        // Έλεγχος επιβεβαίωσης κωδικού
        if (passwordInput.value !== confirmInput.value) {
            confirmError.textContent =
                '❌ Οι κωδικοί πρόσβασης δεν ταιριάζουν.';
            confirmError.style.display = 'block';
            valid = false;
        } else {
            confirmError.style.display = 'none';
        }

        if (!valid) {
            event.preventDefault();
        }
    });
</script>

@endsection
