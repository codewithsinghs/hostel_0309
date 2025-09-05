<body class="bg-gray-100 text-gray-900">
    @include('frontend.layouts.partials.header')

    <main class="container-fluid">
        <div id="app"></div> <!-- Vue will mount here -->
    </main>

    @include('frontend.layouts.partials.footer')

    @vite('resources/js/app.js') <!-- Load Vue -->
    @stack('scripts')
</body>
