<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard') - Jewish House Portal</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #2563eb;
            --secondary-color: #1e40af;
            --success-color: #10b981;
            --danger-color: #ef4444;
            --warning-color: #f59e0b;
            --info-color: #0ea5e9;
            --light-bg: #f8fafc;
            --light-border: #e2e8f0;
            --sidebar-width: 260px;
            --header-height: 70px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f1f5f9;
            color: #334155;
        }

        /* Sidebar Styles */
        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 12px;
            overflow-y: auto;
            z-index: 1000;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
        }

        .sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.3);
            border-radius: 3px;
        }

        .logo {
            text-align: center;
            margin-bottom: 15px;
            padding-bottom: 12px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        }

        .logo .icon {
            font-size: 28px;
            color: white;
            margin-bottom: 5px;
        }

        .logo h3 {
            color: white;
            font-size: 16px;
            font-weight: 700;
            margin-bottom: 2px;
        }

        .logo p {
            color: rgba(255, 255, 255, 0.8);
            font-size: 12px;
        }

        .nav-menu {
            list-style: none;
            margin-left: 0;
            padding-left: 0;
        }

        .nav-menu li {
            margin-bottom: 4px;
        }

        .nav-menu a {
            display: flex;
            align-items: center;
            padding: 10px 12px;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            border-radius: 6px;
            transition: all 0.3s ease;
            font-size: 13px;
            font-weight: 500;
        }

        .nav-menu a:hover {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            transform: translateX(5px);
        }

        .nav-menu a.active {
            background: rgba(255, 255, 255, 0.25);
            color: white;
            border-left: 3px solid white;
        }

        .nav-menu a i {
            width: 20px;
            margin-right: 10px;
            text-align: center;
            font-size: 14px;
        }

        .nav-section-title {
            color: rgba(255, 255, 255, 0.5);
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            margin-top: 12px;
            margin-bottom: 6px;
            padding: 0 12px;
            letter-spacing: 0.4px;
        }

        /* Header Styles */
        .header {
            position: fixed;
            right: 0;
            top: 0;
            left: var(--sidebar-width);
            height: var(--header-height);
            background: white;
            border-bottom: 1px solid var(--light-border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 30px;
            z-index: 999;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 20px;
            flex: 1;
        }

        .breadcrumb-nav {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 14px;
        }

        .breadcrumb-nav a {
            color: var(--primary-color);
            text-decoration: none;
            transition: color 0.3s;
        }

        .breadcrumb-nav a:hover {
            color: var(--secondary-color);
            text-decoration: underline;
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .user-menu {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.3s;
        }

        .user-avatar:hover {
            transform: scale(1.05);
        }

        .user-info {
            display: flex;
            flex-direction: column;
        }

        .user-name {
            font-weight: 600;
            color: #1e293b;
            font-size: 14px;
        }

        .user-role {
            font-size: 12px;
            color: #64748b;
        }

        .dropdown-menu-end {
            min-width: 200px;
        }

        .dropdown-item {
            padding: 10px 16px;
            font-size: 14px;
            transition: all 0.3s;
        }

        .dropdown-item:hover {
            background-color: var(--light-bg);
            color: var(--primary-color);
        }

        .dropdown-divider {
            margin: 5px 0;
        }

        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            margin-top: var(--header-height);
            padding: 30px;
            min-height: 100vh;
        }

        .page-title {
            font-size: 28px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 10px;
        }

        .page-subtitle {
            font-size: 14px;
            color: #64748b;
            margin-bottom: 30px;
        }

        /* Cards */
        .card {
            border: 1px solid var(--light-border);
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            background: white;
        }

        .card:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            transform: translateY(-2px);
        }

        .card-header {
            border-bottom: 1px solid var(--light-border);
            padding: 20px;
            background: var(--light-bg);
            border-radius: 12px 12px 0 0;
        }

        .card-body {
            padding: 20px;
        }

        /* Stat Cards */
        .stat-card {
            background: white;
            border: 1px solid var(--light-border);
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
        }

        .stat-card .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            margin-bottom: 15px;
        }

        .stat-card.blue .stat-icon {
            background: #dbeafe;
            color: var(--primary-color);
        }

        .stat-card.green .stat-icon {
            background: #dcfce7;
            color: var(--success-color);
        }

        .stat-card.orange .stat-icon {
            background: #fed7aa;
            color: var(--warning-color);
        }

        .stat-card.red .stat-icon {
            background: #fee2e2;
            color: var(--danger-color);
        }

        .stat-card .stat-label {
            font-size: 14px;
            color: #64748b;
            margin-bottom: 8px;
            font-weight: 500;
        }

        .stat-card .stat-value {
            font-size: 28px;
            font-weight: 700;
            color: #1e293b;
        }

        /* Buttons */
        .btn {
            border-radius: 8px;
            padding: 10px 18px;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.4);
            color: white;
        }

        .btn-secondary {
            background: var(--light-bg);
            color: #475569;
        }

        .btn-secondary:hover {
            background: var(--light-border);
            color: #1e293b;
        }

        .btn-danger {
            background: var(--danger-color);
            color: white;
        }

        .btn-danger:hover {
            background: #dc2626;
            color: white;
        }

        /* Tables */
        .table-responsive {
            border-radius: 12px;
        }

        .table {
            margin-bottom: 0;
        }

        .table thead th {
            background: var(--light-bg);
            border: 1px solid var(--light-border);
            font-weight: 600;
            color: #64748b;
            padding: 15px;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .table tbody td {
            border: 1px solid var(--light-border);
            padding: 15px;
            vertical-align: middle;
        }

        .table tbody tr:hover {
            background-color: var(--light-bg);
        }

        /* Badges */
        .badge {
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
        }

        .badge-success {
            background: #dcfce7;
            color: #166534;
        }

        .badge-danger {
            background: #fee2e2;
            color: #991b1b;
        }

        .badge-warning {
            background: #fef3c7;
            color: #92400e;
        }

        .badge-info {
            background: #cffafe;
            color: #164e63;
        }

        /* Alerts */
        .alert-box {
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: flex;
            align-items: flex-start;
            gap: 12px;
        }

        .alert-success {
            background: #dcfce7;
            color: #166534;
            border-left: 4px solid #10b981;
        }

        .alert-danger {
            background: #fee2e2;
            color: #991b1b;
            border-left: 4px solid #ef4444;
        }

        .alert-warning {
            background: #fef3c7;
            color: #92400e;
            border-left: 4px solid #f59e0b;
        }

        /* Toggle Sidebar */
        .toggle-sidebar {
            display: none;
            background: none;
            border: none;
            color: #64748b;
            font-size: 20px;
            cursor: pointer;
        }

        /* Responsive */
        @media (max-width: 768px) {
            :root {
                --sidebar-width: 0;
            }

            .sidebar {
                width: 260px;
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .header {
                left: 0;
            }

            .main-content {
                margin-left: 0;
            }

            .toggle-sidebar {
                display: block;
            }

            .page-title {
                font-size: 22px;
            }

            .stat-card {
                margin-bottom: 15px;
            }

            .table {
                font-size: 13px;
            }

            .table thead th,
            .table tbody td {
                padding: 10px;
            }
        }

        /* Animations */
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

        .fade-in {
            animation: slideDown 0.3s ease-out;
        }

        /* Loading Spinner */
        .spinner {
            border: 3px solid var(--light-border);
            border-top: 3px solid var(--primary-color);
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }
            100% {
                transform: rotate(360deg);
            }
        }

        /* Form Styles */
        .form-control {
            border-radius: 8px;
            border: 1px solid var(--light-border);
            padding: 10px 15px;
            font-size: 14px;
            transition: all 0.3s;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
            background-color: #f0f7ff;
        }

        .form-label {
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 8px;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="logo">
            <div class="icon">
                <i class="fas fa-synagogue"></i>
            </div>
            <h3>Admin Portal</h3>
            <p>Jewish House</p>
        </div>

        <ul class="nav-menu">
            <li>
                <a href="{{ route('admin.dashboard') }}" class="@if(Route::currentRouteName() == 'admin.dashboard') active @endif">
                    <i class="fas fa-chart-line"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <div class="nav-section-title">Management</div>

            <li>
                <a href="{{ route('admin.events') }}" class="@if(request()->routeIs('admin.events*')) active @endif">
                    <i class="fas fa-calendar-alt"></i>
                    <span>Events</span>
                </a>
            </li>

            <li>
                <a href="{{ route('admin.registrations') }}" class="@if(Route::currentRouteName() == 'admin.registrations') active @endif">
                    <i class="fas fa-user-check"></i>
                    <span>Registrations</span>
                </a>
            </li>

            <li>
                <a href="{{ route('admin.users') }}" class="@if(Route::currentRouteName() == 'admin.users') active @endif">
                    <i class="fas fa-users"></i>
                    <span>Users</span>
                </a>
            </li>

            <div class="nav-section-title">Analytics</div>

            <li>
                <a href="{{ route('admin.analytics') }}" class="@if(Route::currentRouteName() == 'admin.analytics') active @endif">
                    <i class="fas fa-chart-bar"></i>
                    <span>Analytics</span>
                </a>
            </li>

            <div class="nav-section-title">Settings</div>

            <li>
                <a href="{{ route('admin.settings') }}" class="@if(Route::currentRouteName() == 'admin.settings') active @endif">
                    <i class="fas fa-cog"></i>
                    <span>Settings</span>
                </a>
            </li>

            <li>
                <form method="POST" action="{{ route('admin.logout') }}" style="margin-top: 8px;">
                    @csrf
                    <button type="submit" style="width: 100%; background: none; border: none; padding: 10px 12px; color: rgba(255, 255, 255, 0.8); text-decoration: none; border-radius: 6px; transition: all 0.3s ease; font-size: 13px; font-weight: 500; text-align: left; cursor: pointer; display: flex; align-items: center; gap: 10px;">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Logout</span>
                    </button>
                </form>
            </li>
        </ul>
    </div>

    <!-- Header -->
    <div class="header">
        <div class="header-left">
            <button class="toggle-sidebar" id="toggleSidebar">
                <i class="fas fa-bars"></i>
            </button>
            <div class="breadcrumb-nav">
                <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                <span>/</span>
                <span>@yield('breadcrumb', 'Page')</span>
            </div>
        </div>

        <div class="header-right">
            <div class="user-menu">
                <div class="user-avatar">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <div class="user-info">
                    <div class="user-name">{{ Auth::user()->name }}</div>
                    <div class="user-role">Administrator</div>
                </div>
            </div>
            <div class="dropdown">
                <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    <i class="fas fa-chevron-down"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a class="dropdown-item" href="#">
                            <i class="fas fa-user-circle"></i> Profile
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="#">
                            <i class="fas fa-cog"></i> Account Settings
                        </a>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li>
                        <form method="POST" action="{{ route('admin.logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item" style="background: none; border: none; width: 100%; text-align: left; cursor: pointer;">
                                <i class="fas fa-sign-out-alt"></i> Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        @if (session('success'))
            <div class="alert-box alert-success fade-in">
                <i class="fas fa-check-circle"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if (session('error'))
            <div class="alert-box alert-danger fade-in">
                <i class="fas fa-exclamation-circle"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        @yield('content')
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.min.js"></script>

    <script>
        // Toggle Sidebar on Mobile
        document.getElementById('toggleSidebar')?.addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('show');
        });

        // Close sidebar when clicking on a link (mobile)
        document.querySelectorAll('.nav-menu a').forEach(link => {
            link.addEventListener('click', function() {
                if (window.innerWidth <= 768) {
                    document.querySelector('.sidebar').classList.remove('show');
                }
            });
        });
    </script>

    @yield('scripts')
</body>
</html>
