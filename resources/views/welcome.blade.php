{{-- resources/views/welcome.blade.php --}}

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - WACS System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/perfect-scrollbar/1.5.5/css/perfect-scrollbar.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-image: url('{{ asset('images/bg.jpg') }}');
            background-size: cover;
            background-repeat: no-repeat;
        }
        .navbar-header img {
            height: 40px;
        }
        .left-sidebar {
            background-color: #222;
            color: white;
            padding: 20px;
        }
        .left-sidebar ul {
            list-style-type: none;
            padding-left: 0;
        }
        .left-sidebar ul li {
            padding: 8px 0;
        }
        .page-wrapper {
            padding: 20px;
        }
        .preloader {
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }
    </style>
</head>
<body>
    <div class="preloader">Loading...</div>

    <div id="main-wrapper" class="mini-sidebar">
        <div class="navbar-header">
            <img src="{{ asset('images/logo.jpeg') }}" alt="Logo">
        </div>

        <button class="nav-toggler">☰ Toggle Sidebar</button>
        <button class="nav-lock"><i class="mdi mdi-toggle-switch-off"></i></button>

        <div class="left-sidebar scrollable">
            <ul>
                <li>Dashboard</li>
                <li>Reports</li>
                <li>Settings</li>
            </ul>
        </div>

        <div class="search-box">
            <a href="#"><i class="fas fa-search"></i></a>
            <div class="app-search">
                <input type="text" placeholder="Search..." />
                <a class="srh-btn"><i class="fas fa-times"></i></a>
            </div>
        </div>

        <div class="customizer">
            <button class="service-panel-toggle">⚙ Panel</button>
            <div class="customizer-body scrollable">Panel Content</div>
        </div>

        <div class="page-wrapper">
            <h2>Welcome to the Dashboard</h2>
            <ul class="list-task">
                <li><label><input type="checkbox"> Task One</label></li>
                <li><label><input type="checkbox"> Task Two</label></li>
            </ul>
        </div>
    </div>

    <!-- JS Libraries -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/perfect-scrollbar/1.5.5/perfect-scrollbar.min.js"></script>

    <script>
        $(window).on('load', function () {
            $('.preloader').fadeOut();
        });
    </script>
</body>
</html>

