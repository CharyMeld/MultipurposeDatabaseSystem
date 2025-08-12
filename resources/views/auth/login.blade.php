{{-- resources/views/auth/login.blade.php --}}
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>WACS Login</title>

    {{-- Favicon --}}
    <link rel="shortcut icon" type="image/png" href="{{ asset('assets/images/favicon.png') }}">

    {{-- CSS Files --}}
    <link rel="stylesheet" href="{{ asset('css/styles.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background: url('{{ asset('assets/images/wacs_bg.jpg') }}') no-repeat center center fixed;
            background-size: cover;
        }

        .login-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .login-card {
            background-color: rgba(255, 255, 255, 0.95);
            padding: 30px 40px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            text-align: center;
            width: 100%;
            max-width: 400px;
        }

        .login-card .logo {
            width: 80px;
            margin-bottom: 15px;
        }

        .login-card h2 {
            margin-bottom: 10px;
            font-size: 22px;
        }

        .login-card p {
            margin-bottom: 25px;
            color: #555;
        }

        .login-form .form-group {
            text-align: left;
            margin-bottom: 20px;
        }

        .login-form label {
            display: block;
            font-weight: bold;
            margin-bottom: 6px;
        }

        .login-form input {
            width: 100%;
            padding: 10px;
            border: 1px solid #aaa;
            border-radius: 6px;
            font-size: 14px;
        }

        .login-btn {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 18px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 15px;
            width: 100%;
        }

        .login-btn:hover {
            background-color: #0056b3;
        }

        .error-message {
            margin-top: 10px;
            color: red;
            font-size: 14px;
        }
    </style>
</head>

<body>
    <div class="auth-wrapper">
        <div class="login-container">
            <div class="login-card">
                <img src="{{ asset('assets/images/logo.jpeg') }}" alt="WACS Logo" class="logo" />
                <h2>Welcome to WACS</h2>
                <p>Please login to continue</p>

                {{-- Login Form --}}
                <form method="POST" action="{{ url('login-submit') }}" class="login-form">
                    @csrf

                    <div class="form-group">
                        <label for="login_name">Login Name</label>
                        <input type="email" name="email" required>
                    </div>

                    <div class="form-group">
                        <label for="login_password">Password</label>
                        <input type="password" name="password" required>
                    </div>

                    <button type="submit" class="login-btn">Login</button>
                </form>

                {{-- Error Message --}}
                @if(session('error'))
                    <div class="error-message">{{ session('error') }}</div>
                @endif
            </div>
        </div>
    </div>

    {{-- JS Files --}}
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('js/app.min.js') }}"></script>
</body>

</html>

