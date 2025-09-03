<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Mess Panel</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body>
    <div class="d-flex">
        <nav class="bg-dark text-white p-3" style="width: 250px; height: 100vh;">
            <h4>Mess Panel</h4>

            <ul class="nav flex-column">
                <li class="nav-item">
                    <a href="{{ route('mess.dashboard') }}" class="nav-link text-white">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('messes.index') }}" class="nav-link text-white">Mess Records</a>
                </li>
            </ul>

            <!-- Spacer to push logout to bottom -->
            <div class="flex-grow-1"></div>

            {{-- <!-- Logout Button -->
        <div class="text-left p-3">
            <button type="button" onClick="callLogoutAPI()" class="btn btn-danger">Logout</button>
        </div> --}}

            <form method="POST" action="{{ route('logout') }}" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-danger">Logout</button>
            </form>
        </nav>
        <div class="container p-4">
            @yield('content')
        </div>
    </div>

    {{-- 🔽 Add this before closing body tag --}}
    @yield('scripts')

</body>

</html>
