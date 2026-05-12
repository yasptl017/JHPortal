<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Jewish House Portal</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #2563eb;
            --secondary-color: #1e40af;
            --light-bg: #f8fafc;
            --light-border: #e2e8f0;
        }

        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .login-container {
            max-width: 420px;
            width: 100%;
            background: white;
            border-radius: 12px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            padding: 40px;
            animation: slideUp 0.5s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .login-header .logo {
            font-size: 40px;
            color: var(--primary-color);
            margin-bottom: 15px;
        }

        .login-header h1 {
            font-size: 24px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 5px;
        }

        .login-header p {
            color: #64748b;
            font-size: 14px;
            margin: 0;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .form-control {
            border: 1px solid var(--light-border);
            border-radius: 8px;
            padding: 10px 15px;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
            background-color: #f0f7ff;
        }

        .form-control::placeholder {
            color: #94a3b8;
        }

        .input-group-text {
            border: 1px solid var(--light-border);
            background: white;
            color: #64748b;
        }

        .form-check {
            margin-bottom: 20px;
        }

        .form-check-input {
            width: 18px;
            height: 18px;
            margin-top: 2px;
            border: 1px solid var(--light-border);
            border-radius: 4px;
            cursor: pointer;
        }

        .form-check-input:checked {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .form-check-label {
            color: #475569;
            font-size: 14px;
            margin-left: 8px;
            cursor: pointer;
        }

        .btn-login {
            width: 100%;
            padding: 10px;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 15px;
            transition: all 0.3s ease;
            cursor: pointer;
            margin-bottom: 20px;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(37, 99, 235, 0.3);
            color: white;
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .alert-container {
            margin-bottom: 20px;
        }

        .alert {
            border-radius: 8px;
            border: none;
            padding: 12px 16px;
            font-size: 13px;
            animation: slideDown 0.3s ease-out;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .alert-danger {
            background-color: #fee2e2;
            color: #991b1b;
        }

        .alert-success {
            background-color: #dcfce7;
            color: #166534;
        }

        .divider {
            text-align: center;
            margin: 20px 0;
            color: #cbd5e1;
            font-size: 13px;
        }

        .demo-credentials {
            background: #f0f7ff;
            border: 1px solid #bfdbfe;
            border-radius: 8px;
            padding: 15px;
            margin-top: 20px;
            font-size: 13px;
            color: #1e40af;
        }

        .demo-credentials strong {
            color: #1e3a8a;
        }

        .input-icon {
            position: relative;
        }

        .input-icon i {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 16px;
        }

        .input-icon .form-control {
            padding-left: 40px;
        }

        /* Loading state */
        .btn-login:disabled {
            opacity: 0.7;
            cursor: not-allowed;
        }

        .spinner-border-sm {
            width: 14px;
            height: 14px;
            margin-right: 6px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <!-- Header -->
        <div class="login-header">
            <div class="logo">
                <i class="fas fa-synagogue"></i>
            </div>
            <h1>Admin Portal</h1>
            <p>Jewish House - Event Management System</p>
        </div>

        <!-- Alert Messages -->
        <div class="alert-container">
            @if ($errors->any())
                @foreach ($errors->all() as $error)
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle"></i> {{ $error }}
                    </div>
                @endforeach
            @endif

            @if (session('success'))
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                </div>
            @endif
        </div>

        <!-- Login Form -->
        <form method="POST" action="{{ route('admin.login') }}" novalidate>
            @csrf

            <!-- Email Field -->
            <div class="form-group">
                <label for="email" class="form-label">
                    <i class="fas fa-envelope" style="margin-right: 6px; color: var(--primary-color);"></i>Email Address
                </label>
                <div class="input-icon">
                    <i class="fas fa-envelope"></i>
                    <input 
                        type="email" 
                        class="form-control @error('email') is-invalid @enderror" 
                        id="email" 
                        name="email" 
                        value="{{ old('email') }}"
                        placeholder="admin@example.com" 
                        required 
                        autofocus
                    >
                </div>
            </div>

            <!-- Password Field -->
            <div class="form-group">
                <label for="password" class="form-label">
                    <i class="fas fa-lock" style="margin-right: 6px; color: var(--primary-color);"></i>Password
                </label>
                <div class="input-icon">
                    <i class="fas fa-lock"></i>
                    <input 
                        type="password" 
                        class="form-control @error('password') is-invalid @enderror" 
                        id="password" 
                        name="password" 
                        placeholder="••••••••" 
                        required
                    >
                </div>
            </div>

            <!-- Remember Me -->
            <div class="form-check">
                <input 
                    class="form-check-input" 
                    type="checkbox" 
                    id="remember" 
                    name="remember" 
                    {{ old('remember') ? 'checked' : '' }}
                >
                <label class="form-check-label" for="remember">
                    Keep me logged in
                </label>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn-login">
                <i class="fas fa-sign-in-alt"></i> Sign In
            </button>

            <!-- Demo Credentials -->
            <div class="demo-credentials">
                <div style="margin-bottom: 8px;">
                    <strong><i class="fas fa-info-circle"></i> Demo Credentials:</strong>
                </div>
                <div>Email: <strong>admin@jewishhouse.com</strong></div>
                <div>Password: <strong>admin</strong></div>
            </div>
        </form>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>
