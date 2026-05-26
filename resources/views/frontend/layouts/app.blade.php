<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Jewish House') - Event & Community Portal</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary: #2563eb;
            --primary-dark: #1e40af;
            --secondary: #7c3aed;
            --accent: #0ea5e9;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --dark: #1e293b;
            --gray: #64748b;
            --light: #f8fafc;
            --border: #e2e8f0;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #334155;
            background: #ffffff;
            line-height: 1.6;
        }

        /* Navbar */
        .navbar-main {
            background: #ffffff;
            box-shadow: 0 2px 16px rgba(0,0,0,0.08);
            padding: 12px 0;
            position: sticky;
            top: 0;
            z-index: 1050;
            border-bottom: 1px solid var(--border);
            transition: all 0.3s ease;
        }

        .navbar-main .container { padding: 0 24px; }

        .navbar-brand {
            font-weight: 800;
            font-size: 1.5rem;
            color: var(--primary) !important;
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 0;
            transition: all 0.3s ease;
        }

        .navbar-brand:hover {
            transform: scale(1.02);
        }

        .navbar-brand i {
            font-size: 1.8rem;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .navbar-main .nav-link {
            color: var(--dark) !important;
            font-weight: 500;
            padding: 12px 18px !important;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            position: relative;
            border-radius: 6px;
            margin: 0 4px;
        }

        .navbar-main .nav-link:hover {
            color: var(--primary) !important;
            background: rgba(37, 99, 235, 0.08);
            transform: translateY(-2px);
        }

        .navbar-main .nav-link.active {
            color: var(--primary) !important;
            background: rgba(37, 99, 235, 0.12);
        }

        .navbar-main .nav-link.active::after {
            content: '';
            position: absolute;
            bottom: 8px;
            left: 18px;
            right: 18px;
            height: 3px;
            background: linear-gradient(90deg, var(--primary), var(--secondary));
            border-radius: 3px;
        }

        .btn-nav-login {
            color: var(--primary) !important;
            font-weight: 600;
            font-size: 0.95rem;
            padding: 10px 20px !important;
            transition: all 0.3s ease;
            border-radius: 8px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .btn-nav-login:hover {
            color: var(--primary-dark) !important;
            background: rgba(37, 99, 235, 0.08);
            transform: translateY(-2px);
        }

        .btn-nav-register {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: #fff !important;
            border-radius: 8px;
            padding: 10px 24px !important;
            font-weight: 600;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            border: none;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25);
        }

        .btn-nav-register:hover {
            background: linear-gradient(135deg, var(--primary-dark), #6d28d9);
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(37, 99, 235, 0.35);
            color: #fff !important;
        }

        .btn-nav-register:active {
            transform: translateY(-1px);
        }

        .navbar-auth-section {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-left: 20px;
        }

        .navbar-user-dropdown {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 12px;
            border-radius: 8px;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .navbar-user-dropdown:hover {
            background: rgba(37, 99, 235, 0.08);
        }

        .navbar-user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.9rem;
            box-shadow: 0 2px 8px rgba(37, 99, 235, 0.2);
        }

        .navbar-user-name {
            font-weight: 600;
            font-size: 0.9rem;
            color: var(--dark);
        }

        .dropdown-menu {
            border: 1px solid var(--border);
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
            padding: 8px 0;
        }

        .dropdown-item {
            padding: 10px 16px;
            font-size: 0.9rem;
            transition: all 0.2s ease;
            color: var(--dark);
        }

        .dropdown-item:hover {
            background: rgba(37, 99, 235, 0.08);
            color: var(--primary);
        }

        .dropdown-item i {
            width: 18px;
            color: var(--primary);
        }

        /* Hero / Slider */
        .hero-slider {
            position: relative;
            overflow: hidden;
            height: 550px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .hero-slider .slide {
            position: absolute;
            inset: 0;
            opacity: 0;
            transition: opacity 1s ease;
            display: flex;
            align-items: center;
        }

        .hero-slider .slide.active { opacity: 1; }

        .hero-slider .slide img {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .hero-slider .slide-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(30,41,59,0.7) 0%, rgba(37,99,235,0.4) 100%);
        }

        .hero-slider .slide-content {
            position: relative;
            z-index: 2;
            color: #fff;
            max-width: 650px;
            padding: 0 60px;
        }

        .hero-slider .slide-content h1 {
            font-size: 3rem;
            font-weight: 800;
            margin-bottom: 16px;
            line-height: 1.2;
        }

        .hero-slider .slide-content p {
            font-size: 1.2rem;
            opacity: 0.9;
            margin-bottom: 24px;
        }

        .hero-slider .slide-content .btn-hero {
            background: #fff;
            color: var(--primary);
            padding: 12px 32px;
            border-radius: 8px;
            font-weight: 700;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s;
        }

        .hero-slider .slide-content .btn-hero:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(0,0,0,0.2);
        }

        .slider-dots {
            position: absolute;
            bottom: 30px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 10px;
            z-index: 3;
        }

        .slider-dots .dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: rgba(255,255,255,0.5);
            cursor: pointer;
            transition: all 0.3s;
        }

        .slider-dots .dot.active {
            background: #fff;
            transform: scale(1.2);
        }

        /* Section Styles */
        .section { padding: 80px 0; }
        .section-light { background: var(--light); }

        .section-title {
            font-size: 2.2rem;
            font-weight: 800;
            color: var(--dark);
            margin-bottom: 12px;
        }

        .section-subtitle {
            font-size: 1.1rem;
            color: var(--gray);
            margin-bottom: 40px;
        }

        /* Event Cards */
        .event-card {
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 16px;
            overflow: hidden;
            transition: all 0.3s;
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .event-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 32px rgba(0,0,0,0.1);
        }

        .event-card-img {
            height: 180px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        .event-card-img i { font-size: 3rem; color: rgba(255,255,255,0.6); }

        .event-card-category {
            position: absolute;
            top: 12px;
            left: 12px;
            background: rgba(255,255,255,0.95);
            color: var(--primary);
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 700;
        }

        .event-card-body {
            padding: 20px;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .event-card-body h5 {
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 10px;
            font-size: 1.1rem;
        }

        .event-card-meta {
            display: flex;
            flex-direction: column;
            gap: 6px;
            margin-bottom: 16px;
            font-size: 0.85rem;
            color: var(--gray);
        }

        .event-card-meta i { width: 18px; color: var(--primary); }

        .event-card-footer {
            margin-top: auto;
            padding-top: 16px;
            border-top: 1px solid var(--border);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .event-card-footer .btn-sm {
            background: var(--primary);
            color: #fff;
            border: none;
            padding: 6px 16px;
            border-radius: 6px;
            font-weight: 600;
            font-size: 0.85rem;
            text-decoration: none;
            transition: all 0.2s;
        }

        .event-card-footer .btn-sm:hover {
            background: var(--primary-dark);
        }

        .spots-badge {
            font-size: 0.8rem;
            padding: 4px 10px;
            border-radius: 20px;
            font-weight: 600;
        }

        .spots-available { background: #dcfce7; color: #166534; }
        .spots-limited { background: #fef3c7; color: #92400e; }
        .spots-full { background: #fee2e2; color: #991b1b; }

        /* Features Section */
        .feature-card {
            text-align: center;
            padding: 40px 24px;
            border-radius: 16px;
            background: #fff;
            border: 1px solid var(--border);
            transition: all 0.3s;
            height: 100%;
        }

        .feature-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 24px rgba(0,0,0,0.08);
        }

        .feature-icon {
            width: 70px;
            height: 70px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 1.8rem;
        }

        .feature-icon.blue { background: #dbeafe; color: var(--primary); }
        .feature-icon.green { background: #dcfce7; color: var(--success); }
        .feature-icon.purple { background: #ede9fe; color: var(--secondary); }
        .feature-icon.orange { background: #fed7aa; color: #ea580c; }

        .feature-card h5 { font-weight: 700; color: var(--dark); margin-bottom: 10px; }
        .feature-card p { color: var(--gray); font-size: 0.9rem; }

        /* Footer */
        .footer {
            background: var(--dark);
            color: #cbd5e1;
            padding: 60px 0 30px;
        }

        .footer h5 {
            color: #fff;
            font-weight: 700;
            margin-bottom: 20px;
            font-size: 1.1rem;
        }

        .footer a {
            color: #94a3b8;
            text-decoration: none;
            transition: color 0.2s;
        }

        .footer a:hover { color: #fff; }

        .footer-links { list-style: none; padding: 0; }
        .footer-links li { margin-bottom: 10px; }
        .footer-links li a { font-size: 0.9rem; }

        .footer-bottom {
            border-top: 1px solid #334155;
            padding-top: 24px;
            margin-top: 40px;
            text-align: center;
            font-size: 0.85rem;
            color: #64748b;
        }

        .social-links { display: flex; gap: 12px; }
        .social-links a {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            background: #334155;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #94a3b8;
            transition: all 0.3s;
        }
        .social-links a:hover {
            background: var(--primary);
            color: #fff;
        }

        /* Utility */
        .btn-primary-custom {
            background: var(--primary);
            color: #fff;
            border: none;
            padding: 12px 28px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s;
        }
        .btn-primary-custom:hover {
            background: var(--primary-dark);
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(37,99,235,0.3);
        }

        .btn-outline-custom {
            background: transparent;
            color: var(--primary);
            border: 2px solid var(--primary);
            padding: 10px 28px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s;
        }
        .btn-outline-custom:hover {
            background: var(--primary);
            color: #fff;
        }

        /* Page Header */
        .page-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 60px 0;
            color: #fff;
        }

        .page-header h1 {
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 8px;
        }

        .page-header p { opacity: 0.9; font-size: 1.1rem; }

        .breadcrumb-custom {
            display: flex;
            gap: 8px;
            list-style: none;
            padding: 0;
            margin: 16px 0 0;
            font-size: 0.9rem;
        }

        .breadcrumb-custom a { color: rgba(255,255,255,0.8); text-decoration: none; }
        .breadcrumb-custom a:hover { color: #fff; }
        .breadcrumb-custom .separator { color: rgba(255,255,255,0.5); }
        .breadcrumb-custom .current { color: #fff; font-weight: 600; }

        /* Alert */
        .alert-custom {
            border-radius: 10px;
            padding: 14px 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 20px;
            border: none;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .hero-slider { height: 400px; }
            .hero-slider .slide-content { padding: 0 24px; }
            .hero-slider .slide-content h1 { font-size: 2rem; }
            .hero-slider .slide-content p { font-size: 1rem; }
            .section { padding: 50px 0; }
            .section-title { font-size: 1.7rem; }
            .page-header { padding: 40px 0; }
            .page-header h1 { font-size: 1.8rem; }
        }

        @media (max-width: 576px) {
            .hero-slider { height: 350px; }
            .hero-slider .slide-content h1 { font-size: 1.6rem; }
            .navbar-main .nav-link { padding: 12px 16px !important; }
        }
    </style>
    @yield('styles')
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-main">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <i class="fas fa-synagogue"></i>
                Jewish House
            </a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav" aria-label="Toggle navigation">
                <i class="fas fa-bars" style="color: var(--dark);"></i>
            </button>
            <div class="collapse navbar-collapse" id="mainNav">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item">
                        <a class="nav-link @if(Route::currentRouteName() == 'home') active @endif" href="{{ route('home') }}">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link @if(request()->routeIs('events.*')) active @endif" href="{{ route('events.index') }}">Events</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link @if(Route::currentRouteName() == 'about') active @endif" href="{{ route('about') }}">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link @if(Route::currentRouteName() == 'contact') active @endif" href="{{ route('contact') }}">Contact</a>
                    </li>
                </ul>
                <div class="navbar-auth-section">
                    @auth
                        <div class="dropdown">
                            <a class="navbar-user-dropdown dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <div class="navbar-user-avatar">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </div>
                                <span class="navbar-user-name d-none d-md-inline">{{ Auth::user()->name }}</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="{{ route('profile') }}"><i class="fas fa-user"></i>My Profile</a></li>
                                @if(Auth::user()->is_admin)
                                    <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}"><i class="fas fa-cog"></i>Admin Panel</a></li>
                                @endif
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item"><i class="fas fa-sign-out-alt"></i>Logout</button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="btn-nav-login">
                            <i class="fas fa-sign-in-alt"></i>
                            <span>Login</span>
                        </a>
                        <a href="{{ route('register') }}" class="btn-nav-register">
                            <i class="fas fa-user-plus"></i>
                            <span>Register</span>
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="container mt-3">
            <div class="alert alert-success alert-custom">
                <i class="fas fa-check-circle"></i>
                {{ session('success') }}
            </div>
        </div>
    @endif
    @if(session('error'))
        <div class="container mt-3">
            <div class="alert alert-danger alert-custom">
                <i class="fas fa-exclamation-circle"></i>
                {{ session('error') }}
            </div>
        </div>
    @endif

    <!-- Main Content -->
    @yield('content')

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4 col-md-6">
                    <h5><i class="fas fa-synagogue me-2"></i>Jewish House</h5>
                    <p style="font-size:0.9rem;margin-bottom:20px;">
                        Connecting our community through meaningful events, programs, and engagement opportunities.
                    </p>
                    <div class="social-links">
                        <a href="#" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                        <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6">
                    <h5>Quick Links</h5>
                    <ul class="footer-links">
                        <li><a href="{{ route('home') }}">Home</a></li>
                        <li><a href="{{ route('events.index') }}">Events</a></li>
                        <li><a href="{{ route('about') }}">About Us</a></li>
                        <li><a href="{{ route('contact') }}">Contact</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h5>Programs</h5>
                    <ul class="footer-links">
                        <li><a href="{{ route('events.index') }}?category=JH+Kids">JH Kids</a></li>
                        <li><a href="{{ route('events.index') }}?category=SMART+Recovery">SMART Recovery</a></li>
                        <li><a href="{{ route('events.index') }}?category=Movement+Program">Movement Programs</a></li>
                        <li><a href="{{ route('events.index') }}?category=Parent+Program">Parent Programs</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h5>Contact Info</h5>
                    <ul class="footer-links">
                        <li><i class="fas fa-envelope me-2"></i>info@jewishhouse.org.au</li>
                        <li><i class="fas fa-phone me-2"></i>(02) 9300 0000</li>
                        <li><i class="fas fa-map-marker-alt me-2"></i>Sydney, NSW, Australia</li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; {{ date('Y') }} Jewish House. All rights reserved. Event & Community Engagement Portal.</p>
            </div>
        </div>
    </footer>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>
