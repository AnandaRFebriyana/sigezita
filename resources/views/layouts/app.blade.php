<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') | SiGizi Balita</title>

    <!-- Custom fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap & SB Admin 2 via CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap4.min.css">

    <style>
        /* ============================================================
           SB Admin 2 Custom Styles
        ============================================================ */
        :root {
            --primary: #1a8a54;
            --primary-dark: #0d5c3a;
            --success: #2ec278;
            --info: #38bdf8;
            --warning: #f97316;
            --danger: #e74a3b;
            --secondary: #4b7a62;
            --light: #f8fdf9;
            --dark: #0a1f14;
            --sidebar-width: 224px;
        }

        body {
            font-family: 'Nunito', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background-color: #f8f9fc;
            font-size: 0.875rem;
        }

        /* ---- Sidebar ---- */
        #wrapper { display: flex; }

        #sidebar-wrapper {
            min-height: 100vh;
            width: var(--sidebar-width);
            background: linear-gradient(160deg, #0d5c3a 0%, #1a8a54 70%, #2ec278 100%);
            position: fixed;
            left: 0;
            top: 0;
            z-index: 1000;
            transition: width 0.25s ease;
            overflow-y: auto;
            overflow-x: hidden;
        }

        #sidebar-wrapper.toggled {
            width: 0;
            overflow: hidden;
        }

        .sidebar-brand {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem 1rem;
            border-bottom: 1px solid rgba(255,255,255,0.15);
        }

        .sidebar-brand-icon {
            font-size: 2rem;
            color: #fff;
        }

        .sidebar-brand-text {
            color: #fff;
            font-weight: 800;
            font-size: 1rem;
            text-transform: uppercase;
            letter-spacing: 0.05rem;
            margin-left: 0.5rem;
            line-height: 1.2;
        }

        .sidebar-brand-text small {
            font-size: 0.65rem;
            font-weight: 600;
            opacity: 0.7;
            display: block;
        }

        .sidebar-divider {
            border-top: 1px solid rgba(255,255,255,0.15);
            margin: 0 1rem;
        }

        .sidebar-heading {
            color: rgba(255,255,255,0.4);
            font-size: 0.65rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.13rem;
            padding: 0 1rem;
            margin: 1rem 0 0.5rem;
        }

        .nav-item .nav-link {
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            color: rgba(255,255,255,0.8);
            font-weight: 700;
            font-size: 0.85rem;
            transition: all 0.2s;
        }

        .nav-item .nav-link:hover,
        .nav-item .nav-link.active {
            color: #fff;
            background: rgba(255,255,255,0.15);
            border-radius: 0.35rem;
        }

        .nav-item .nav-link i {
            font-size: 0.85rem;
            margin-right: 0.5rem;
            width: 1.2rem;
            text-align: center;
        }

        /* ---- Main Content ---- */
        #content-wrapper {
            margin-left: var(--sidebar-width);
            width: calc(100% - var(--sidebar-width));
            min-height: 100vh;
            transition: margin-left 0.25s ease, width 0.25s ease;
            display: flex;
            flex-direction: column;
        }

        #content-wrapper.toggled {
            margin-left: 0;
            width: 100%;
        }

        /* ---- Topbar ---- */
        .topbar {
            background: #fff;
            border-bottom: 1px solid rgba(46,194,120,0.15);
            box-shadow: 0 0.15rem 1.75rem rgba(58,59,69,0.15);
            height: 4.375rem;
            display: flex;
            align-items: center;
            padding: 0 1.5rem;
            position: sticky;
            top: 0;
            z-index: 999;
        }

        .topbar-divider {
            width: 0;
            border-right: 1px solid #e3e6f0;
            height: 2rem;
            margin: 0 1rem;
        }

        .topbar .btn-link {
            color: var(--secondary);
        }

        .topbar .nav-item .nav-link {
            padding: 0 0.75rem;
            height: 4.375rem;
            display: flex;
            align-items: center;
            color: var(--secondary);
        }

        .topbar .nav-item .nav-link:hover {
            background: #f8f9fc;
        }

        .user-info .user-name {
            font-weight: 700;
            font-size: 0.85rem;
            color: var(--dark);
        }

        .user-info .user-role {
            font-size: 0.75rem;
            color: var(--secondary);
        }

        .avatar {
            width: 2.5rem;
            height: 2.5rem;
            border-radius: 50%;
            background: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-weight: 700;
            font-size: 1rem;
        }

        /* ---- Cards ---- */
        .card {
            border: none;
            border-radius: 0.35rem;
            box-shadow: 0 0.15rem 1.75rem rgba(58,59,69,0.1);
            margin-bottom: 1.5rem;
        }

        .card-header {
            background: #fff;
            border-bottom: 1px solid #e3e6f0;
            padding: 0.75rem 1.25rem;
            font-weight: 700;
            font-size: 0.85rem;
            color: var(--primary);
            text-transform: uppercase;
            letter-spacing: 0.05rem;
        }

        /* ---- Stat Cards ---- */
        .border-left-primary  { border-left: 0.25rem solid var(--primary) !important; }
        .border-left-success  { border-left: 0.25rem solid var(--success) !important; }
        .border-left-info     { border-left: 0.25rem solid var(--info) !important; }
        .border-left-warning  { border-left: 0.25rem solid var(--warning) !important; }
        .border-left-danger   { border-left: 0.25rem solid var(--danger) !important; }

        .stat-card .stat-icon {
            font-size: 2rem;
            opacity: 0.3;
        }

        .stat-card .stat-label {
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05rem;
            color: var(--secondary);
            margin-bottom: 0.15rem;
        }

        .stat-card .stat-value {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--dark);
        }

        /* ---- Badges ---- */
        .badge-success { background-color: var(--success); color: #fff; }
        .badge-warning { background-color: var(--warning); color: #fff; }
        .badge-danger  { background-color: var(--danger); color: #fff; }
        .badge-info    { background-color: var(--info); color: #fff; }
        .badge-secondary { background-color: var(--secondary); color: #fff; }

        /* ---- Tables ---- */
        .table thead th {
            background: #f8f9fc;
            border-top: none;
            font-size: 0.75rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.05rem;
            color: var(--secondary);
        }

        /* ---- Buttons ---- */
        .btn { font-weight: 700; border-radius: 0.35rem; font-size: 0.8rem; }
        .btn-primary  { background-color: var(--primary); border-color: var(--primary); }
        .btn-success  { background-color: var(--success); border-color: var(--success); }
        .btn-info     { background-color: var(--info);    border-color: var(--info); }
        .btn-warning  { background-color: var(--warning); border-color: var(--warning); color: #fff; }
        .btn-danger   { background-color: var(--danger);  border-color: var(--danger); }

        /* ---- Page Header ---- */
        .page-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.5rem;
        }

        .page-header h1 {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--dark);
            margin: 0;
        }

        .breadcrumb {
            background: transparent;
            padding: 0;
            margin: 0;
            font-size: 0.75rem;
        }

        /* ---- Alert ---- */
        .alert { border: none; border-radius: 0.35rem; font-size: 0.85rem; }

        /* ---- Form ---- */
        .form-control {
            border: 1px solid #d1d3e2;
            border-radius: 0.35rem;
            font-size: 0.875rem;
            color: var(--dark);
        }

        .form-control:focus {
            border-color: #bac8f3;
            box-shadow: 0 0 0 0.2rem rgba(78,115,223,0.25);
        }

        .form-label {
            font-weight: 600;
            font-size: 0.8rem;
            color: var(--dark);
            margin-bottom: 0.35rem;
        }

        /* ---- Status Badge large ---- */
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.4rem 0.8rem;
            border-radius: 2rem;
            font-size: 0.8rem;
            font-weight: 700;
        }

        .status-badge.normal     { background: #d4edda; color: #155724; }
        .status-badge.berisiko   { background: #fff3cd; color: #856404; }
        .status-badge.stunting   { background: #f8d7da; color: #721c24; }

        /* ---- Z-Score indicator ---- */
        .zscore-bar { height: 8px; border-radius: 4px; background: #e3e6f0; position: relative; margin-top: 4px; }
        .zscore-fill { height: 100%; border-radius: 4px; transition: width 0.5s ease; }
        .zscore-normal  { background: var(--success); }
        .zscore-warning { background: var(--warning); }
        .zscore-danger  { background: var(--danger); }

        /* ---- Scrollbar ---- */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: #c8c8c8; border-radius: 3px; }

        /* ---- Print ---- */
        @media print {
            #sidebar-wrapper, .topbar, .no-print { display: none !important; }
            #content-wrapper { margin-left: 0; width: 100%; }
            .card { box-shadow: none; border: 1px solid #dee2e6; }
        }

        /* ---- Responsive ---- */
        @media (max-width: 768px) {
            #sidebar-wrapper { width: 0; overflow: hidden; }
            #sidebar-wrapper.toggled { width: var(--sidebar-width); }
            #content-wrapper { margin-left: 0; width: 100%; }
            #content-wrapper.toggled { margin-left: var(--sidebar-width); }
        }
    </style>

    @stack('styles')
</head>
<body id="page-top">
<div id="wrapper">

    <!-- ========== SIDEBAR ========== -->
    <div id="sidebar-wrapper">
        <div class="sidebar-brand">
            <div class="sidebar-brand-icon">
                <i class="fas fa-baby"></i>
            </div>
            <div class="sidebar-brand-text ms-2" style="margin-left:0.5rem">
                SiGizi
                <small>Sistem Gizi Balita</small>
            </div>
        </div>

        <hr class="sidebar-divider">

        <div class="sidebar-heading">Menu Utama</div>

        <ul class="nav flex-column px-2" style="list-style:none;padding:0">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('balita.*') ? 'active' : '' }}" href="{{ route('balita.index') }}">
                    <i class="fas fa-child"></i> Data Balita
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('pengukuran.*') ? 'active' : '' }}" href="{{ route('pengukuran.index') }}">
                    <i class="fas fa-weight"></i> Pengukuran
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('laporan.*') ? 'active' : '' }}" href="{{ route('laporan.index') }}">
                    <i class="fas fa-file-alt"></i> Laporan
                </a>
            </li>

            @if(auth()->user()->isAdmin())
            <hr class="sidebar-divider">
            <div class="sidebar-heading">Administrasi</div>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.posyandu.*') ? 'active' : '' }}" href="{{ route('admin.posyandu.index') }}">
                    <i class="fas fa-hospital"></i> Posyandu
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
                    <i class="fas fa-users-cog"></i> Manajemen Petugas
                </a>
            </li>
            @endif
        </ul>

        <hr class="sidebar-divider" style="margin-top:auto">
        <div style="padding:1rem;color:rgba(255,255,255,0.4);font-size:0.65rem;text-align:center">
            SiGizi v1.0 &copy; {{ date('Y') }}<br>
        </div>
    </div>
    <!-- ========== END SIDEBAR ========== -->

    <!-- ========== CONTENT WRAPPER ========== -->
    <div id="content-wrapper">

        <!-- Topbar -->
        <nav class="topbar">
            <button id="sidebarToggle" class="btn btn-link border-0 p-0 mr-3" style="margin-right:1rem">
                <i class="fas fa-bars fa-fw" style="color:#858796"></i>
            </button>

            <nav aria-label="breadcrumb" class="d-none d-md-block">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    @yield('breadcrumb')
                </ol>
            </nav>

            <div class="ml-auto d-flex align-items-center" style="margin-left:auto">
                <div class="topbar-divider"></div>
                <div class="nav-item dropdown">
                    <a class="nav-link d-flex align-items-center" href="#" data-toggle="dropdown">
                        <div class="avatar mr-2" style="margin-right:0.5rem">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <div class="user-info d-none d-md-block">
                            <div class="user-name">{{ auth()->user()->name }}</div>
                            <div class="user-role">{{ auth()->user()->isAdmin() ? 'Administrator' : 'Petugas Posyandu' }}</div>
                        </div>
                        <i class="fas fa-chevron-down ml-2 fa-xs" style="margin-left:0.5rem;color:#858796"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right shadow">
                        <div class="dropdown-header">
                            <strong>{{ auth()->user()->name }}</strong><br>
                            <small class="text-muted">{{ auth()->user()->email }}</small>
                        </div>
                        <div class="dropdown-divider"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger">
                                <i class="fas fa-sign-out-alt fa-fw mr-2"></i> Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </nav>
        <!-- End Topbar -->

        <!-- Main Content -->
        <main class="container-fluid py-4 px-4">
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
            </div>
            @endif

            @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle mr-2"></i> {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
            </div>
            @endif

            @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                <strong>Terdapat kesalahan input:</strong>
                <ul class="mb-0 mt-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
            </div>
            @endif

            @yield('content')
        </main>

        <footer class="mt-auto py-3 px-4" style="background:#fff;border-top:1px solid #e3e6f0;font-size:0.75rem;color:#858796;text-align:center">
            SiGizi &mdash; Sistem Identifikasi Status Gizi Balita &copy; {{ date('Y') }}
        </footer>
    </div>
    <!-- ========== END CONTENT WRAPPER ========== -->
</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<script>
// Sidebar toggle
$('#sidebarToggle').on('click', function () {
    $('#sidebar-wrapper').toggleClass('toggled');
    $('#content-wrapper').toggleClass('toggled');
});

// Auto dismiss alerts
setTimeout(function () {
    $('.alert-dismissible').fadeOut('slow');
}, 5000);

// DataTable default
$.fn.dataTable.defaults.language = {
    url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json'
};
</script>

@stack('scripts')
</body>
</html>