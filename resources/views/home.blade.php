@extends('layouts.app')

@section('title', 'Αρχική Σελίδα')

@section('content')
    @auth
        <!-- Αν ο χρήστης είναι συνδεδεμένος -->
        Γεια σου {{ auth()->user()->name }}!
    @else
        <!-- Αν ο χρήστης δεν είναι συνδεδεμένος -->
        Δεν είστε συνδεδεμένος/η.
    @endauth

    <!-- Εμφάνιση μηνυμάτων session αν υπάρχουν -->
    @if(session('success'))
        {{ session('success') }}
    @endif

    @if(session('error'))
        {{ session('error') }}
    @endif
@endsection
