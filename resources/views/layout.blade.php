<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consultancy Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-size: .875rem; background-color: #f8f9fa; }
        .feather { width: 16px; height: 16px; vertical-align: text-bottom; }
        
        /* Sidebar styling */
        .sidebar { position: fixed; top: 0; bottom: 0; left: 0; z-index: 100; padding: 48px 0 0; box-shadow: inset -1px 0 0 rgba(0, 0, 0, .1); }
        .sidebar-sticky { position: relative; top: 0; height: calc(100vh - 48px); padding-top: .5rem; overflow-x: hidden; overflow-y: auto; }
        .nav-link { font-weight: 500; color: #333; }
        .nav-link.active { color: #0d6efd; }
        
        .navbar-brand { padding-top: .75rem; padding-bottom: .75rem; font-size: 1rem; background-color: rgba(0, 0, 0, .25); box-shadow: inset -1px 0 0 rgba(0, 0, 0, .25); }
        .card { border: none; box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075); }
    </style>
</head>
<body>

<header class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0 shadow">
    <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3" href="#">ExpertConsult Admin</a>
    <button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="navbar-nav w-100 d-flex flex-row justify-content-end px-3">
        <div class="nav-item text-nowrap">
            <a class="nav-link px-3 text-white" href="#">Sign out</a>
        </div>
    </div>
</header>

<div class="container-fluid">
    <div class="row">
        <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
            <div class="sidebar-sticky pt-3">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link active" href="#"><i data-feather="home"></i> Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('countries.index') }}"><i data-feather="users"></i> Country</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('states.index') }}"><i data-feather="calendar"></i> State</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#"><i data-feather="file-text"></i> Invoices</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#"><i data-feather="bar-chart-2"></i> Reports</a>
                    </li>
                </ul>
            </div>
        </nav>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            @yield('content')
        </main>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>

<script>
    $(document).ready(function() {
        // Initialize Feather Icons
        feather.replace();

        // Simple jQuery Interaction
        $('#addBtn').on('click', function() {
            alert('Opening "Add New Consultation" Modal...');
        });

        $('.view-btn').on('click', function() {
            const rowId = $(this).closest('tr').find('td:first').text();
            alert('Viewing details for Appointment ' + rowId);
        });

        // Dynamic update example
        $('#clientCount').fadeIn(500).fadeOut(500).fadeIn(500);
    });
</script>
</body>
</html>