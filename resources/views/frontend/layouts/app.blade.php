<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <!-- Meta Tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="@yield('meta_description', 'Default site description')">
    <meta name="author" content="Your Company Name">

    <!-- Title -->
    <title>@yield('title', config('app.name'))</title>

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

    <!-- Global Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <!-- Page-specific Styles -->
    @stack('styles')

    <!-- External Libraries (Modern Hierarchy) -->
    <!-- Example: Tailwind, Alpine.js, FontAwesome -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body class="bg-gray-100 text-gray-900">

    <!-- Header -->
    @include('partials.header')

    <!-- Main Container -->
    <main class="container mx-auto px-4 py-6">
        @yield('content')
    </main>

    <!-- Footer -->
    @include('partials.footer')

    <!-- Global Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>

    <!-- Page-specific Scripts -->
    @stack('scripts')

    <!-- Optional: Livewire or Vue support -->
    @livewireScripts
</body>

</html>
