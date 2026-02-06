<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', config('app.name'))</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <style>
            *, ::after, ::before { box-sizing: border-box; margin: 0; padding: 0; }
            body { font-family: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif; line-height: 1.5; background-color: #fdfdfc; color: #1b1b18; }
            .flex { display: flex; } .flex-col { flex-direction: column; } .flex-1 { flex: 1; }
            .items-center { align-items: center; } .justify-center { justify-content: center; }
            .gap-4 { gap: 1rem; } .min-h-screen { min-height: 100vh; }
            .p-6 { padding: 1.5rem; } .mb-4 { margin-bottom: 1rem; } .mb-6 { margin-bottom: 1.5rem; }
            .w-full { width: 100%; } .max-w-4xl { max-width: 56rem; }
            .text-sm { font-size: 0.875rem; } .text-2xl { font-size: 1.5rem; line-height: 2rem; }
            .font-medium { font-weight: 500; }
            .text-\[\#1b1b18\] { color: #1b1b18; }
            .text-\[\#706f6c\] { color: #706f6c; }
            .navbar { display: flex; align-items: center; justify-content: space-between; width: 100%; flex-wrap: wrap; gap: 1rem; }
            .navbar-left { flex-shrink: 0; }
            .navbar-tabs { flex: 1; display: flex; justify-content: center; align-items: center; gap: 0.25rem; flex-wrap: wrap; list-style: none; margin: 0; padding: 0; }
            .navbar-right { flex-shrink: 0; }
            .navbar-brand { font-weight: 600; font-size: 1.125rem; color: #1b1b18; text-decoration: none; }
            .navbar-tabs a { display: inline-block; padding: 0.5rem 1rem; border-radius: 0.25rem; font-size: 0.875rem; font-weight: 500; text-decoration: none; color: #1b1b18; transition: background-color 0.15s, color 0.15s; }
            .navbar-tabs a:hover { background-color: #e3e3e0; }
            .navbar-tabs a.active { background-color: #1b1b18; color: #fff; }
            .navbar-profile { display: inline-block; padding: 0.5rem 1rem; border-radius: 0.25rem; font-size: 0.875rem; font-weight: 500; text-decoration: none; color: #1b1b18; transition: background-color 0.15s, color 0.15s; }
            .navbar-profile:hover { background-color: #e3e3e0; }
            .navbar-profile.active { background-color: #1b1b18; color: #fff; }
            @media (prefers-color-scheme: dark) {
                body { background-color: #0a0a0a; color: #ededec; }
                .dark\:text-\[\#EDEDEC\] { color: #ededec; }
                .dark\:text-\[\#A1A09A\] { color: #a1a09a; }
                .navbar-brand { color: #ededec; }
                .navbar-tabs a { color: #ededec; }
                .navbar-tabs a:hover { background-color: #3e3e3a; }
                .navbar-tabs a.active { background-color: #ededec; color: #1b1b18; }
                .navbar-profile { color: #ededec; }
                .navbar-profile:hover { background-color: #3e3e3a; }
                .navbar-profile.active { background-color: #ededec; color: #1b1b18; }
            }
        </style>
    @endif
</head>
<body class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] dark:text-[#EDEDEC] min-h-screen flex flex-col p-6">
    <header class="w-full max-w-4xl text-sm mb-6">
        @include('partials.navbar')
    </header>
    <main class="flex-1 w-full max-w-4xl">
        @yield('content')
    </main>
</body>
</html>
