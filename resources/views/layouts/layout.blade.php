<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'WACS System')</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('assets/images/favicon.ico') }}">

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('assets/styles.css') }}">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        * {
            box-sizing: border-box;
        }

        html, body {
            height: 100%;
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            overflow-x: hidden;
        }

        .wrapper {
            display: flex;
            min-height: 100vh;
            width: 100%;
        }

        .sidebar {
            width: 260px;
            background-color: #1f2937;
            color: white;
            padding: 20px;
            flex-shrink: 0;
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            overflow-y: auto;
            z-index: 1030;
            box-shadow: 2px 0 5px rgba(0,0,0,0.1);
            transition: transform 0.3s ease-in-out;
        }

        .sidebar .logo {
            text-align: center;
            margin-bottom: 30px;
        }

        .sidebar .logo img {
            max-width: 180px;
            max-height: 80px;
            object-fit: contain;
            display: block;
            margin: 0 auto;
        }

        .sidebar .logo .fallback-text {
            font-size: 24px;
            font-weight: bold;
            color: #fff;
            display: none;
        }

        .sidebar .nav-link {
            color: #d1d5db;
            padding: 12px 15px;
            display: block;
            text-decoration: none;
            border-radius: 6px;
            margin-bottom: 5px;
            transition: all 0.3s ease;
        }

        .sidebar .nav-link:hover {
            background: #374151;
            color: #fff;
            transform: translateX(5px);
        }

        .sidebar .nav-link.active {
            background: #3b82f6;
            color: #fff;
        }

        .content {
            flex: 1;
            margin-left: 260px;
            padding: 30px;
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .mobile-toggle {
                display: block;
                position: fixed;
                top: 15px;
                left: 15px;
                z-index: 1040;
                background: #1f2937;
                color: white;
                border: none;
                padding: 10px;
                border-radius: 5px;
                cursor: pointer;
            }

            .content {
                margin-left: 0;
                padding: 20px;
            }
        }

        @media (min-width: 769px) {
            .mobile-toggle {
                display: none;
            }
        }
    </style>
</head>
<body>

    <!-- Mobile Sidebar Toggle Button -->
    <button class="mobile-toggle d-md-none" onclick="toggleSidebar()">☰</button>

    <div class="wrapper">
        <!-- Sidebar -->
        <div class="sidebar" id="sidebar">
            <div class="logo">
                <img src="{{ asset('assets/images/logo.jpeg') }}" alt="WACS Logo"
                     onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                <div class="fallback-text">WACS</div>
            </div>
            @php
                use App\Models\Menu;
                $menus = Menu::with('children')->get();
            @endphp

            @include('partials.sidebar', ['menus' => $menus])
        </div>

        <!-- Main Content -->
        <div class="content">
            @yield('content')
        </div>
    </div>

    @yield('scripts')

    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('show');
        }

        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('sidebar');
            const toggle = document.querySelector('.mobile-toggle');
            if (window.innerWidth <= 768 &&
                !sidebar.contains(event.target) &&
                !toggle.contains(event.target) &&
                sidebar.classList.contains('show')) {
                sidebar.classList.remove('show');
            }
        });

        window.addEventListener('resize', function() {
            if (window.innerWidth > 768) {
                document.getElementById('sidebar').classList.remove('show');
            }
        });
    </script>
</body>
</html>

