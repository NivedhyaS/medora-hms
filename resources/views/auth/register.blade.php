<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Medora Hospital</title>

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

        /* --- Image Side (Laptop View) --- */
        .auth-image-side {
            flex: 1.2;
            position: sticky;
            top: 0;
            height: 100vh;
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
            max-width: 480px;
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
            margin-bottom: 30px;
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
            overflow-y: auto;
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

        .register-card {
            width: 100%;
            max-width: 650px;
        }

        .register-header {
            margin-bottom: 35px;
        }

        .register-header h2 {
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 8px;
            font-size: 32px;
        }

        .register-header p {
            color: #64748b;
            font-size: 16px;
        }

        .section-title {
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: #94a3b8;
            font-weight: 700;
            margin: 30px 0 20px 0;
            border-bottom: 2px solid #f1f5f9;
            padding-bottom: 8px;
            display: block;
        }

        .form-label {
            font-weight: 600;
            color: #475569;
            font-size: 14px;
            margin-bottom: 8px;
        }

        .form-control,
        .form-select {
            padding: 12px 16px;
            border-radius: 12px;
            border: 1.5px solid #e2e8f0;
            font-size: 15px;
            transition: all 0.2s ease;
            background: #f8fafc;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
            background: white;
            outline: none;
        }

        .btn-register {
            background: var(--primary-gradient);
            color: white;
            padding: 14px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 17px;
            border: none;
            width: 100%;
            margin-top: 30px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2);
        }

        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(37, 99, 235, 0.3);
            opacity: 0.95;
            color: white;
        }

        .login-link {
            text-align: center;
            margin-top: 30px;
            font-size: 15px;
            color: #64748b;
        }

        .login-link a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 700;
        }

        .login-link a:hover {
            text-decoration: underline;
        }

        @media (max-width: 991px) {
            .auth-image-side {
                display: none;
            }

            .auth-form-side {
                padding: 40px 20px;
                background: #f8fafc;
            }

            .register-card {
                background: white;
                padding: 40px;
                border-radius: 24px;
                box-shadow: 0 10px 40px rgba(0, 0, 0, 0.05);
            }

            .home-btn {
                top: 20px;
                right: 20px;
            }
        }

        @media (max-width: 576px) {
            .register-card {
                padding: 30px 20px;
            }

            .register-header h2 {
                font-size: 26px;
            }
        }
    </style>
</head>

<body>

    <div class="auth-container">
        <!-- Image Side (Laptop View) -->
        <div class="auth-image-side">
            <div class="auth-image-content">
                <h1>Join the Future of Patient Care.</h1>
                <p>Register today to unlock personalized medical services, manage your appointments, and stay connected
                    with your healthcare team.</p>
            </div>
        </div>
        <!-- Form Side -->
        <div class="auth-form-side">
            <a href="{{ url('/') }}" class="home-btn" title="Back to Home">
                <i class="fas fa-home"></i>
            </a>

            <div class="register-card">
                <div class="register-header">
                    <div class="mb-4 text-center text-lg-start">
                        <a href="{{ url('/') }}">
                            <img src="{{ asset('img/logo.svg') }}" alt="Medora Hospital Logo" style="height: 65px;">
                        </a>
                    </div>
                    <h2>Create Account</h2>
                    <p>Register as a patient to get started</p>
                </div>

                @if ($errors->any())
                    <div class="alert alert-danger mb-4 shadow-sm border-0">
                        <ul class="mb-0 small">
                            @foreach ($errors->all() as $error)
                                <li><i class="fas fa-exclamation-circle me-1"></i> {{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('auth.register.submit') }}">
                    @csrf

                    <div class="section-title">Personal Information</div>

                    <div class="mb-3">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="name" class="form-control" required value="{{ old('name') }}">
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Gender</label>
                            <select name="gender" class="form-select" required>
                                <option value="">Select Gender</option>
                                <option {{ old('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                                <option {{ old('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                                <option {{ old('gender') == 'Other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Date of Birth</label>
                            <input type="date" name="dob" class="form-control" required value="{{ old('dob') }}">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Blood Group</label>
                            <select name="blood_group" class="form-select" required>
                                <option value="">Select Group</option>
                                @foreach (['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $group)
                                    <option {{ old('blood_group') == $group ? 'selected' : '' }}>{{ $group }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Mobile Number</label>
                            <input type="text" name="mobile" class="form-control" required value="{{ old('mobile') }}">
                        </div>
                    </div>

                    <div class="section-title">Contact & Security</div>

                    <div class="mb-3">
                        <label class="form-label">Email Address</label>
                        <input type="email" name="email" class="form-control" required value="{{ old('email') }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Residential Address</label>
                        <textarea name="address" rows="2" class="form-control" required></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Emergency Contact</label>
                        <input type="text" name="emergency_contact" class="form-control" required
                            value="{{ old('emergency_contact') }}">
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" minlength="8" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Confirm Password</label>
                            <input type="password" name="password_confirmation" class="form-control" minlength="8"
                                required>
                        </div>
                    </div>

                    <button type="submit" class="btn-register">
                        Register Account <i class="fas fa-user-plus ms-2 small"></i>
                    </button>

                    <div class="login-link">
                        Already have an account? <a href="{{ route('auth.login') }}">Go to Login</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

</body>

</html>