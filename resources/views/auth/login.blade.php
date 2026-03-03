<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Medora Hospital</title>

    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('img/favicon.svg') }}">

    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        :root {
            --primary: #2563eb;
            --primary-gradient: linear-gradient(135deg, #4f46e5 0%, #0ea5e9 100%);
            --sidebar-bg: #111827;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #f8fafc;
            margin: 0;
            overflow-x: hidden;
        }

        .auth-container {
            display: flex;
            min-height: 100vh;
            width: 100%;
        }

        /* --- Image Side (Laptop View Only) --- */
        .auth-image-side {
            flex: 1.2;
            position: relative;
            background-image: url("{{ asset('img/auth_panel.png') }}");
            background-size: cover;
            background-position: center;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 60px;
            color: white;
            z-index: 10;
        }

        .auth-image-side::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(37, 99, 235, 0.8) 0%, rgba(17, 24, 39, 0.8) 100%);
            z-index: -1;
        }

        .auth-image-content {
            max-width: 500px;
        }

        .auth-image-content h1 {
            font-size: 48px;
            font-weight: 800;
            margin-bottom: 24px;
            line-height: 1.2;
        }

        .auth-image-content p {
            font-size: 18px;
            opacity: 0.9;
            line-height: 1.6;
        }

        /* --- Form Side --- */
        .auth-form-side {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
            background: white;
            position: relative;
        }

        .home-btn {
            position: absolute;
            top: 30px;
            right: 30px;
            width: 44px;
            height: 44px;
            background: white;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            text-decoration: none;
            color: #64748b;
            border: 1px solid #e2e8f0;
            z-index: 100;
        }

        .home-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            color: var(--primary);
            border-color: var(--primary);
        }

        .login-card {
            width: 100%;
            max-width: 420px;
            padding: 20px 0;
        }

        .login-header {
            margin-bottom: 35px;
        }

        .login-header h2 {
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 8px;
            font-size: 32px;
        }

        .login-header p {
            color: #64748b;
            font-size: 16px;
        }

        .form-label {
            font-weight: 600;
            color: #475569;
            font-size: 14px;
            margin-bottom: 8px;
        }

        .form-control {
            padding: 12px 16px;
            border-radius: 12px;
            border: 1.5px solid #e2e8f0;
            font-size: 15px;
            transition: all 0.2s ease;
            background: #f8fafc;
        }

        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
            background: white;
            outline: none;
        }

        .btn-login {
            background: var(--primary-gradient);
            color: white;
            padding: 14px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 16px;
            border: none;
            width: 100%;
            margin-top: 20px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2);
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(37, 99, 235, 0.3);
            opacity: 0.95;
            color: white;
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .register-link {
            text-align: center;
            margin-top: 30px;
            font-size: 15px;
            color: #64748b;
        }

        .register-link a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 700;
        }

        .register-link a:hover {
            text-decoration: underline;
        }

        .alert {
            border-radius: 12px;
            font-size: 14px;
            padding: 14px 18px;
            border: none;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.03);
        }

        /* --- Laptop/Mobile View Logic --- */
        @media (max-width: 991px) {
            .auth-image-side {
                display: none;
            }

            .auth-form-side {
                padding: 30px;
            }

            .login-card {
                max-width: 100%;
            }

            .home-btn {
                top: 20px;
                right: 20px;
            }
        }

        @media (max-width: 576px) {
            .auth-form-side {
                background: #f8fafc;
            }

            .login-card {
                background: white;
                padding: 30px;
                border-radius: 20px;
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            }
        }
    </style>
</head>

<body>

    <div class="auth-container">
        <!-- Image Side (Laptop View) -->
        <div class="auth-image-side">
            <div class="auth-image-content">
                <h1>Advanced Healthcare, Compassionate Care.</h1>
                <p>Welcome to Medora Hospital's secure portal. Manage your health journey with precision and ease using
                    our professional care ecosystem.</p>
            </div>
        </div>

        <!-- Form Side -->
        <div class="auth-form-side">
            <a href="{{ url('/') }}" class="home-btn" title="Back to Home">
                <i class="fas fa-home"></i>
            </a>

            <div class="login-card">
                <div class="login-header">
                    <div class="mb-4 text-center text-lg-start">
                        <a href="{{ url('/') }}">
                            <img src="{{ asset('img/logo.svg') }}" alt="Medora Hospital Logo" style="height: 65px;">
                        </a>
                    </div>
                    <h2>Login</h2>
                    <p>Enter your details to access your portal</p>
                </div>

                @if(session('success'))
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li><i class="fas fa-exclamation-circle me-1"></i> {{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('auth.login.submit') }}">
                    @csrf

                    <div class="mb-4">
                        <label class="form-label">Email Address</label>
                        <input type="email" name="email" class="form-control" required autofocus>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>

                    <div class="mb-3 form-check d-flex align-items-center">
                        <input type="checkbox" class="form-check-input mt-0" id="remember" name="remember">
                        <label class="form-check-label ps-2 text-sm text-secondary" for="remember">Keep me signed
                            in</label>
                    </div>

                    <button type="submit" class="btn-login">
                        Sign In <i class="fas fa-arrow-right ms-2 small"></i>
                    </button>

                    <div class="register-link">
                        Don't have an account? <a href="{{ route('auth.register') }}">Create an account</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

</body>

</html>