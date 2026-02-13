@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <!-- Προσωποποιημένο μήνυμα -->
    @if(auth()->check())
        Γεια σου {{ auth()->user()->name }}!
    @endif

    <!-- Μηνύματα session -->
    @if(session('success'))
        {{ session('success') }}
    @endif

    @if(session('error'))
        {{ session('error') }}
    @endif

    <!-- Εδώ μπορεί να μπει η φόρμα ή άλλο περιεχόμενο -->
@endsection
