<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Library App') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>

        @if (session('success'))
            <div style="
                margin: 20px auto;
                padding: 12px 16px;
                max-width: 500px;
                background-color: #d4edda;
                color: #155724;
                border: 1px solid #c3e6cb;
                border-radius: 6px;
                text-align: center;
                font-weight: 600;
            ">
                {{ session('success') }}
            </div>
        @endif

        body { margin: 0; font-family: "Segoe UI", Tahoma, sans-serif; background: #ffffff; }
        .page-wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            flex-direction: column;
            text-align: center;
            padding: 20px;
            background-image: linear-gradient(rgba(255,255,255,0.75), rgba(255,255,255,0.75)), url("{{ asset('images/books_background.jpg') }}");
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }
        nav { display: flex; flex-direction: column; align-items: center; gap: 12px; padding: 18px 28px; }
        .nav-row { display: flex; flex-wrap: wrap; justify-content: center; gap: 12px; }
        nav a {
            text-decoration: none; color: #1f2937; font-weight: 700; font-size: 1.15rem;
            padding: 8px 14px; border: 2px solid #d6c9b8; border-radius: 8px; background-color: #f5f0e6;
            transition: transform 0.2s ease, color 0.2s ease, background 0.2s ease;
        }
        nav a:hover { transform: translateY(-2px); background-color: #e0d4bf; color: #4f46e5; }
        nav form button {
            background: #f5f0e6; color: #c0392b; border: 2px solid #d6c9b8; padding: 10px 18px; cursor: pointer;
            font-weight: 700; font-size: 1.15rem; border-radius: 8px;
            transition: transform 0.2s ease, background 0.2s ease, box-shadow 0.2s ease;
        }
        nav form button:hover { transform: translateY(-2px); background: #e0d4bf; box-shadow: 0 4px 12px rgba(231, 76, 60, 0.5); color: #c0392b; }
        hr { width: 65%; margin: 30px auto; border: none; height: 3px; border-radius: 2px; background: linear-gradient(to right, #4f46e5, #22c55e); }
        .content-box { padding: 35px; max-width: 900px; width: 100%; font-size: 1.15rem; font-weight: 500; color: #1f2937; animation: fadeIn 0.6s ease-in-out; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(15px);} to { opacity: 1; transform: translateY(0);} }
        @media (max-width: 600px) {
            nav { padding: 14px; }
            nav a, nav form button { font-size: 1rem; padding: 6px 10px; }
            .content-box { padding: 22px; font-size: 1rem; }
        }
    </style>
</head>
<body>
<div class="page-wrapper">
    @include('layouts.navigation')
    <hr>
    <div class="content-box">
        @if (session('status'))
            <div style="margin-bottom:12px;padding:12px;border-left:4px solid #2196F3;background:#e3f2fd;border-radius:4px;">
                {{ session('status') }}
            </div>
        @endif

        {{ $slot }}
    </div>
</div>
</body>
</html>
