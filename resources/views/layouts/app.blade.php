<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Sales Performance') }} - Admin Portal</title>
    
    <!-- Google Fonts (Outfit) -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
    <!-- DataTables Bootstrap 5 CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
    
    <style>
        :root {
            --bg-primary: #0b0f19;
            --bg-secondary: #111827;
            --bg-card: #1f2937;
            --text-primary: #f3f4f6;
            --text-secondary: #9ca3af;
            --accent-primary: #6366f1;
            --accent-hover: #4f46e5;
            --success: #10b981;
            --danger: #ef4444;
            --warning: #f59e0b;
        }

        body {
            font-family: 'Outfit', sans-serif;
            background-color: var(--bg-primary);
            color: var(--text-primary);
            min-height: 100vh;
            display: flex;
            overflow-x: hidden;
        }

        /* Sidebar Styling */
        .sidebar {
            width: 260px;
            background-color: var(--bg-secondary);
            border-right: 1px solid rgba(255, 255, 255, 0.05);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 100;
            transition: all 0.3s ease;
        }

        .sidebar-brand {
            padding: 1.5rem;
            font-size: 1.25rem;
            font-weight: 700;
            color: #fff;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            text-decoration: none;
        }

        .sidebar-menu {
            list-style: none;
            padding: 1rem;
            margin: 0;
            flex-grow: 1;
        }

        .sidebar-item {
            margin-bottom: 0.5rem;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1rem;
            color: var(--text-secondary);
            text-decoration: none;
            border-radius: 0.5rem;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .sidebar-link:hover {
            color: #fff;
            background-color: rgba(255, 255, 255, 0.05);
        }

        .sidebar-link.active {
            color: #fff;
            background: linear-gradient(135deg, var(--accent-primary), var(--accent-hover));
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
        }

        .sidebar-footer {
            padding: 1rem;
            border-top: 1px solid rgba(255, 255, 255, 0.05);
        }

        /* Main Content Styling */
        .main-wrapper {
            margin-left: 260px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            width: calc(100% - 260px);
        }

        .navbar {
            background-color: var(--bg-secondary);
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            padding: 1rem 2rem;
        }

        .content-area {
            padding: 2rem;
            flex-grow: 1;
        }

        /* Cards & Widgets */
        .card {
            background-color: var(--bg-card);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 1rem;
            color: var(--text-primary);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .card-header {
            background-color: rgba(255, 255, 255, 0.02);
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            padding: 1.25rem 1.5rem;
            font-weight: 600;
        }

        /* Forms styling */
        .form-control, .form-select {
            background-color: rgba(0, 0, 0, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: #fff;
            border-radius: 0.5rem;
        }

        .form-control:focus, .form-select:focus {
            background-color: rgba(0, 0, 0, 0.3);
            border-color: var(--accent-primary);
            box-shadow: 0 0 0 0.25rem rgba(99, 102, 241, 0.25);
            color: #fff;
        }

        /* Custom Table Design */
        .table {
            color: var(--text-primary);
        }
        .table th {
            background-color: rgba(255, 255, 255, 0.02) !important;
            color: var(--text-secondary);
            border-bottom: 2px solid rgba(255, 255, 255, 0.05);
            font-weight: 600;
        }
        .table td {
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            vertical-align: middle;
        }

        /* Rank Badges */
        .badge-gold {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: #fff;
        }
        .badge-silver {
            background: linear-gradient(135deg, #9ca3af, #4b5563);
            color: #fff;
        }
        .badge-bronze {
            background: linear-gradient(135deg, #b45309, #78350f);
            color: #fff;
        }

        /* Buttons */
        .btn-primary {
            background: linear-gradient(135deg, var(--accent-primary), var(--accent-hover));
            border: none;
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, var(--accent-hover), #4338ca);
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        ::-webkit-scrollbar-track {
            background: var(--bg-primary);
        }
        ::-webkit-scrollbar-thumb {
            background: var(--bg-card);
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: var(--text-secondary);
        }

        @media (max-width: 991.98px) {
            .sidebar {
                transform: translateX(-100%);
            }
            .sidebar.active {
                transform: translateX(0);
            }
            .main-wrapper {
                margin-left: 0;
                width: 100%;
            }
        }
    </style>
    @yield('styles')
</head>
<body>

    <!-- Sidebar Navigation -->
    <aside class="sidebar">
        <a href="{{ route('dashboard') }}" class="sidebar-brand">
            <i class="fa-solid fa-trophy text-warning"></i>
            <span>RankMaster</span>
        </a>
        <ul class="sidebar-menu">
            <li class="sidebar-item">
                <a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->is('/') || request()->is('dashboard*') ? 'active' : '' }}">
                    <i class="fa-solid fa-chart-line"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="sidebar-item">
                <a href="{{ route('departments.index') }}" class="sidebar-link {{ request()->is('departments*') ? 'active' : '' }}">
                    <i class="fa-solid fa-sitemap"></i>
                    <span>Departments</span>
                </a>
            </li>
            <li class="sidebar-item">
                <a href="{{ route('users.index') }}" class="sidebar-link {{ request()->is('users*') ? 'active' : '' }}">
                    <i class="fa-solid fa-users"></i>
                    <span>Salespersons</span>
                </a>
            </li>
            <li class="sidebar-item">
                <a href="{{ route('targets.index') }}" class="sidebar-link {{ request()->is('targets*') ? 'active' : '' }}">
                    <i class="fa-solid fa-bullseye"></i>
                    <span>Monthly Targets</span>
                </a>
            </li>
            <li class="sidebar-item">
                <a href="{{ route('sales.index') }}" class="sidebar-link {{ request()->is('sales*') ? 'active' : '' }}">
                    <i class="fa-solid fa-hand-holding-dollar"></i>
                    <span>Sales Management</span>
                </a>
            </li>
        </ul>
        <div class="sidebar-footer">
            <div class="d-flex align-items-center justify-content-between">
                <div class="small text-truncate" style="max-width: 150px;">
                    <i class="fa-solid fa-user-shield me-2"></i>{{ Auth::user()->name }}
                </div>
                <form action="{{ route('logout') }}" method="POST" class="m-0">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-danger border-0" title="Logout">
                        <i class="fa-solid fa-right-from-bracket"></i>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- Main Wrapper -->
    <div class="main-wrapper">
        <header class="navbar d-flex justify-content-between align-items-center px-4">
            <button class="btn btn-outline-secondary d-lg-none" id="sidebar-toggle">
                <i class="fa-solid fa-bars"></i>
            </button>
            <div class="ms-auto d-flex align-items-center gap-3">
                <span class="badge bg-secondary p-2">
                    <i class="fa-solid fa-calendar me-1"></i> {{ date('F Y') }}
                </span>
            </div>
        </header>

        <main class="content-area">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show border-0 bg-success text-white shadow-sm mb-4" role="alert" style="--bs-bg-opacity: 0.2;">
                    <i class="fa-solid fa-circle-check me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show border-0 bg-danger text-white shadow-sm mb-4" role="alert" style="--bs-bg-opacity: 0.2;">
                    <i class="fa-solid fa-circle-exclamation me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <script>
        $(document).ready(function() {
            // Mobile Sidebar Toggle
            $('#sidebar-toggle').click(function() {
                $('.sidebar').toggleClass('active');
            });
        });
    </script>
    @yield('scripts')
</body>
</html>
