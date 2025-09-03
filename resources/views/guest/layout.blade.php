<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Guest Panel</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</head>

<body>
    <div class="d-flex" style="height: 100vh; overflow: hidden;">
    <!-- Sidebar -->
    <nav class="bg-dark text-white p-3"
         style="width: 250px; min-height: 100px; flex-shrink: 0;">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a href="{{ url('/guest/dashboard') }}" class="nav-link text-white">
                    <i class="fas fa-home"></i> Dashboard
                </a>
            </li>
            <!-- Add more nav items as needed -->
        </ul>

        <!-- Logout Button -->
        <div class="mt-auto">
            <button type="button" onClick="callLogoutAPI()" class="btn btn-danger w-100">Logout</button>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="flex-grow-1 overflow-auto container-fluid p-4">
        @yield('content')
    </div>
</div>




    <!-- FontAwesome Icons -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            if (!localStorage.getItem('token') && !localStorage.getItem('token')) {
                callLogoutAPI();
            } else if (localStorage.getItem('token') && !localStorage.getItem('auth-id')) {
                callLogoutAPI();
            } else if (!localStorage.getItem('token') && localStorage.getItem('auth-id')) {
                callLogoutAPI();
            } else {
                $.ajax({
                    url: '/api/guest/profile', // your API endpoint
                    type: 'GET',
                    headers: {
                        'token': localStorage.getItem('token'),
                        'auth-id': localStorage.getItem('auth-id')
                    },
                    success: function(response) {
                        if (!response.success) {
                            callLogoutAPI();
                        }
                    },
                });
            }

        });

        function callLogoutAPI() {
            $.ajax({
                url: '/api/logout',
                type: 'POST',
                headers: {
                    'token': localStorage.getItem('token'),
                    'Auth-ID': localStorage.getItem('auth-id')
                },
                complete: function() {
                    localStorage.removeItem('token');
                    localStorage.removeItem('auth-id');
                    window.location.href = "/login";
                }
            });
        }
    </script>
    @yield('page-scripts')
</body>

</html>
