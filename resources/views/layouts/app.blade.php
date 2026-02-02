<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Consultancy Admin Panel') - ExpertConsult</title>
    <meta name="description" content="Professional consultancy management system">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #0d6efd;
            --secondary-color: #6c757d;
            --success-color: #198754;
            --warning-color: #ffc107;
            --danger-color: #dc3545;
            --info-color: #0dcaf0;
            --light-color: #f8f9fa;
            --dark-color: #212529;
            --sidebar-width: 280px;
            --navbar-height: 60px;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            font-size: 0.9rem;
            line-height: 1.6;
            background-color: var(--light-color);
            color: var(--dark-color);
        }

        /* Navbar */
        .navbar {
            height: var(--navbar-height);
            box-shadow: 0 2px 4px rgba(0,0,0,0.08);
            z-index: 1030;
        }

        .navbar-brand {
            font-weight: 600;
            font-size: 1.1rem;
            padding: 0.75rem 1rem;
        }

        /* Sidebar */
        .sidebar {
            position: fixed;
            top: var(--navbar-height);
            left: 0;
            bottom: 0;
            width: var(--sidebar-width);
            z-index: 1020;
            background: #fff;
            border-right: 1px solid #dee2e6;
            transform: translateX(-100%);
            transition: transform 0.3s ease-in-out;
            overflow-y: auto;
        }

        .sidebar.show {
            transform: translateX(0);
        }

        @media (min-width: 992px) {
            .sidebar {
                transform: translateX(0);
            }
            
            .sidebar.collapsed {
                transform: translateX(-100%);
            }
        }

        .sidebar-sticky {
            position: relative;
            top: 0;
            height: calc(100vh - var(--navbar-height));
            padding: 1rem 0;
            overflow-x: hidden;
            overflow-y: auto;
        }

        .nav-link {
            font-weight: 500;
            color: #6c757d;
            padding: 0.75rem 1.25rem;
            margin: 0.125rem 0.75rem;
            border-radius: 0.5rem;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .nav-link:hover {
            color: var(--primary-color);
            background-color: rgba(13, 110, 253, 0.08);
        }

        .nav-link.active {
            color: var(--primary-color);
            background-color: rgba(13, 110, 253, 0.1);
        }

        .nav-link i {
            width: 18px;
            height: 18px;
        }

        /* Main Content */
        .main-content {
            margin-left: 0;
            padding-top: var(--navbar-height);
            min-height: 100vh;
            transition: margin-left 0.3s ease-in-out;
        }

        @media (min-width: 992px) {
            .main-content {
                margin-left: var(--sidebar-width);
            }
            
            .main-content.expanded {
                margin-left: 0;
            }
        }

        .content-wrapper {
            padding: 2rem;
        }

        @media (max-width: 768px) {
            .content-wrapper {
                padding: 1rem;
            }
        }

        /* Cards */
        .card {
            border: none;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            border-radius: 0.75rem;
            transition: box-shadow 0.15s ease-in-out;
        }

        .card:hover {
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }

        .card-header {
            background-color: #fff;
            border-bottom: 1px solid #dee2e6;
            border-radius: 0.75rem 0.75rem 0 0 !important;
            padding: 1.25rem;
        }

        /* Tables */
        .table {
            font-size: 0.875rem;
        }

        .table th {
            font-weight: 600;
            color: #495057;
            border-top: none;
            background-color: #f8f9fa;
        }

        /* Forms */
        .form-control, .form-select {
            border-radius: 0.5rem;
            border: 1px solid #dee2e6;
            padding: 0.625rem 0.875rem;
            font-size: 0.875rem;
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
        }

        .form-label {
            font-weight: 500;
            margin-bottom: 0.5rem;
            color: #495057;
        }

        /* Buttons */
        .btn {
            border-radius: 0.5rem;
            font-weight: 500;
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
            transition: all 0.15s ease-in-out;
        }

        .btn-sm {
            padding: 0.375rem 0.75rem;
            font-size: 0.8125rem;
        }

        /* Breadcrumb */
        .breadcrumb {
            background-color: transparent;
            padding: 0;
            margin-bottom: 0.5rem;
            font-size: 0.875rem;
        }

        .breadcrumb-item + .breadcrumb-item::before {
            content: ">";
            color: #6c757d;
        }

        /* Mobile optimizations */
        @media (max-width: 576px) {
            .table-responsive {
                font-size: 0.8125rem;
            }
            
            .btn-group .btn {
                padding: 0.25rem 0.5rem;
            }
            
            .card-body {
                padding: 1rem;
            }
        }

        /* Utility classes */
        .text-truncate-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .feather {
            width: 16px;
            height: 16px;
            vertical-align: text-bottom;
        }

        /* Modern Pagination Styles */
        .pagination {
            margin-bottom: 0;
        }

        .pagination .page-link {
            border: none;
            color: var(--primary-color);
            padding: 0.5rem 0.75rem;
            margin: 0 0.125rem;
            border-radius: 0.5rem;
            font-weight: 500;
            transition: all 0.2s ease;
            background-color: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            min-width: 40px;
        }

        .pagination .page-link:hover {
            background-color: var(--primary-color);
            color: white;
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .pagination .page-item.active .page-link {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            color: white;
            box-shadow: 0 2px 4px rgba(13, 110, 253, 0.3);
        }

        .pagination .page-item.disabled .page-link {
            color: #6c757d;
            background-color: #f8f9fa;
            opacity: 0.6;
        }

        .pagination .page-item:first-child .page-link,
        .pagination .page-item:last-child .page-link {
            margin: 0 0.25rem;
        }

        /* Ensure Feather icons in pagination are properly sized */
        .pagination .page-link i {
            width: 14px !important;
            height: 14px !important;
        }

        /* Responsive pagination */
        @media (max-width: 576px) {
            .pagination {
                justify-content: center;
                flex-wrap: wrap;
            }
            
            .pagination .page-link {
                padding: 0.375rem 0.625rem;
                font-size: 0.875rem;
                margin: 0.0625rem;
                min-width: 32px;
            }
            
            .pagination .page-link i {
                width: 12px !important;
                height: 12px !important;
            }
        }
    </style>
</head>
<body>

<!-- Navbar -->
<header class="navbar navbar-dark bg-dark fixed-top">
    <div class="container-fluid">
        <button class="navbar-toggler d-lg-none me-2" type="button" id="sidebarToggle">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <a class="navbar-brand text-white" href="{{ route('dashboard') }}">
            <i data-feather="layers" class="me-2"></i>
            ExpertConsult Admin
        </a>
        
        <div class="navbar-nav ms-auto">
            <div class="nav-item dropdown">
                <a class="nav-link dropdown-toggle text-white" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                    <i data-feather="user" class="me-1"></i>
                    Admin
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="#"><i data-feather="user" class="me-2"></i>Profile</a></li>
                    <li><a class="dropdown-item" href="#"><i data-feather="settings" class="me-2"></i>Settings</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="#"><i data-feather="log-out" class="me-2"></i>Sign out</a></li>
                </ul>
            </div>
        </div>
    </div>
</header>

<!-- Sidebar -->
<nav class="sidebar" id="sidebar">
    <div class="sidebar-sticky">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                    <i data-feather="home"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            
            <li class="nav-item">
                <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
                    <span>Management</span>
                </h6>
            </li>
            
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('countries.*') ? 'active' : '' }}" href="{{ route('countries.index') }}">
                    <i data-feather="globe"></i>
                    <span>Countries</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('states.*') ? 'active' : '' }}" href="{{ route('states.index') }}">
                    <i data-feather="map-pin"></i>
                    <span>States</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('branches.*') ? 'active' : '' }}" href="{{ route('branches.index') }}">
                    <i data-feather="briefcase"></i>
                    <span>Branches</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('employees.*') ? 'active' : '' }}" href="{{ route('employees.index') }}">
                    <i data-feather="users"></i>
                    <span>Employees</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('agents.*') ? 'active' : '' }}" href="{{ route('agents.index') }}">
                    <i data-feather="user-check"></i>
                    <span>Agents</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('universities.*') ? 'active' : '' }}" href="{{ route('universities.index') }}">
                    <i data-feather="book-open"></i>
                    <span>Universities</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('courses.*') ? 'active' : '' }}" href="{{ route('courses.index') }}">
                    <i data-feather="book"></i>
                    <span>Courses</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('students.*') ? 'active' : '' }}" href="{{ route('students.index') }}">
                    <i data-feather="user"></i>
                    <span>Students</span>
                </a>
            </li>
            
            <li class="nav-item">
                <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
                    <span>Applications</span>
                </h6>
            </li>
            
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('intakes.*') ? 'active' : '' }}" href="{{ route('intakes.index') }}">
                    <i data-feather="calendar"></i>
                    <span>Intakes</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('application-years.*') ? 'active' : '' }}" href="{{ route('application-years.index') }}">
                    <i data-feather="clock"></i>
                    <span>Application Years</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('application-status.*') ? 'active' : '' }}" href="{{ route('application-status.index') }}">
                    <i data-feather="check-circle"></i>
                    <span>Application Status</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('student-applications.*') ? 'active' : '' }}" href="{{ route('student-applications.index') }}">
                    <i data-feather="file-text"></i>
                    <span>Student Applications</span>
                </a>
            </li>
        </ul>
    </div>
</nav>

<!-- Main Content -->
<main class="main-content" id="mainContent">
    <div class="content-wrapper">
        @yield('content')
    </div>
</main>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Feather Icons
        feather.replace();
        
        // Sidebar toggle for mobile
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebar = document.getElementById('sidebar');
        const mainContent = document.getElementById('mainContent');
        
        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', function() {
                sidebar.classList.toggle('show');
            });
        }
        
        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(event) {
            if (window.innerWidth < 992) {
                if (!sidebar.contains(event.target) && !sidebarToggle.contains(event.target)) {
                    sidebar.classList.remove('show');
                }
            }
        });
        
        // Handle window resize
        window.addEventListener('resize', function() {
            if (window.innerWidth >= 992) {
                sidebar.classList.remove('show');
            }
        });
    });
</script>

@stack('scripts')

</body>
</html>