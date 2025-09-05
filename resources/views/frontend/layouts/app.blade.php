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
    <!-- Swiper CSS -->
    <link rel="stylesheet" href="{{ asset('frontend/css/swiper-bundle.min.css') }}" />
    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <!-- Bootstrap  v5.2.3 -->
    <link rel="stylesheet" href="{{ asset('frontend/css/bootstrap.css') }}">
    <!-- css -->
    <link rel="stylesheet" href="{{ asset('frontend/css/common.css') }}" />
    <link rel="stylesheet" href="{{ asset('frontend/css/index.css') }}" />
    <link rel="stylesheet" href="{{ asset('frontend/css/register.css') }}" />

    <!-- Page-specific Styles -->
    @stack('styles')
</head>

<body class="bg-gray-100 text-gray-900">

    <!-- Header -->
    @include('frontend.layouts.partials.header')

    <!-- Main Container -->
    {{-- <main class="container-fluid mx-auto px-4 py-6"> --}}
    <main class="container-fluid">
        @yield('content')
    </main>

    <!-- Footer -->
    @include('frontend.layouts.partials.footer')

    <!-- Global Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/vue@3/dist/vue.global.prod.js"></script>

    <!-- Page-specific Scripts -->
    @stack('scripts')
</body>

</html>
