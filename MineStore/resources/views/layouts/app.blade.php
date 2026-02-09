<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'Laravel'))</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    <!-- Minecrafter Alt Font -->
    <link rel="stylesheet" href="https://db.onlinewebfonts.com/c/dc7947b63602675ec87b023cfc35d028?family=Minecrafter+Alt" />

    <!-- Styles / Scripts -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        {{-- Fallback CSS lorsque Vite n'est pas encore compilé --}}
        <link rel="stylesheet" href="{{ asset('css/fallback.css') }}">
        <script src="{{ asset('js/app.js') }}"></script>
    @endif
</head>
<body class="bg-white text-[#1b1b18] min-h-screen flex flex-col">
    {{-- Navbar --}}
    @include('partials.navbar')

    {{-- Contenu principal --}}
    <main class="flex-1 w-full">
        @yield('content')
    </main>

    {{-- Scripts --}}
    @stack('scripts')
</body>
</html>
