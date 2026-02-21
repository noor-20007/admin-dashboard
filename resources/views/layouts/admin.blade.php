<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>لوحة التحكم | @yield('title', 'AdminLTE 4')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes" />
    <meta name="color-scheme" content="light dark" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200..1000&display=swap" rel="stylesheet">
    
    <!-- Third Party Plugin(OverlayScrollbars) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/styles/overlayscrollbars.min.css" crossorigin="anonymous" />
    
    <!-- Third Party Plugin(Bootstrap Icons) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.min.css" crossorigin="anonymous" />
    
    <!-- AdminLTE CSS -->
    <link rel="stylesheet" href="{{ asset('adminlte/css/adminlte.rtl.min.css') }}" />
    
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700;800&display=swap');

        :root {
            --primary: #4361ee;
            --primary-dark: #3f37c9;
            --secondary: #4895ef;
            --accent: #4cc9f0;
            --success: #06d6a0;
            --warning: #ffd166;
            --danger: #ef476f;
            --dark: #2b2d42;
            --light: #f8f9fa;
            --body-bg: #f3f4f6;
            --card-bg: #ffffff;
            --border-radius: 16px;
            --card-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.01);
            --hover-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        body {
            font-family: 'Cairo', sans-serif;
            background-color: var(--body-bg);
            color: var(--dark);
        }

        /* --- Creative Cards --- */
        .card {
            border: none;
            border-radius: var(--border-radius);
            background: var(--card-bg);
            box-shadow: var(--card-shadow);
            transition: var(--transition);
            margin-bottom: 1.5rem;
            overflow: hidden;
        }

        .card:hover {
            box-shadow: var(--hover-shadow);
            transform: translateY(-4px);
        }

        .card-header {
            background: transparent;
            border-bottom: 1px solid rgba(0,0,0,0.04);
            padding: 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .card-title {
            font-weight: 800;
            color: var(--primary);
            font-size: 1.25rem;
            margin: 0;
            letter-spacing: -0.5px;
        }

        /* --- Modern Tables --- */
        .table-responsive {
            border-radius: var(--border-radius);
            overflow-x: auto;
            -webkit-overflow-scrolling: touch; /* Smooth scrolling on iOS */
        }

        .table {
            margin-bottom: 0;
            vertical-align: middle;
            white-space: nowrap; /* Prevent text wrapping for better density */
        }

        .table thead th {
            background-color: #f9fafb;
            color: #6b7280;
            text-transform: uppercase;
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 0.05em;
            border-bottom: 1px solid #e5e7eb;
            padding: 0.8rem 0.75rem; /* Reduced padding */
        }

        .table tbody td {
            padding: 0.75rem 0.75rem; /* Reduced padding for cleaner, tighter rows */
            color: #374151;
            font-weight: 500;
            border-bottom: 1px solid #f3f4f6;
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background-color: #fcfdfe;
        }

        .table tbody tr:hover {
            background-color: #eff6ff;
        }

        /* --- Soft Forms --- */
        .form-control, .form-select {
            border-radius: 12px;
            border: 2px solid #e5e7eb;
            padding: 0.75rem 1rem;
            font-size: 0.95rem;
            transition: var(--transition);
            background-color: #f9fafb;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(67, 97, 238, 0.15);
            background-color: #fff;
        }

        .form-label {
            font-weight: 600;
            color: #4b5563;
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
        }

        /* --- Buttons --- */
        .btn {
            border-radius: 10px;
            padding: 0.6rem 1.2rem;
            font-weight: 600;
            transition: var(--transition);
            letter-spacing: 0.3px;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            border: none;
            box-shadow: 0 4px 6px -1px rgba(67, 97, 238, 0.4);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(67, 97, 238, 0.5);
            background: linear-gradient(135deg, var(--primary-dark), var(--primary));
        }

        .btn-danger {
            background: linear-gradient(135deg, var(--danger), #d90429);
            border: none;
            box-shadow: 0 4px 6px -1px rgba(239, 71, 111, 0.4);
        }

        .btn-danger:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(239, 71, 111, 0.5);
        }

        /* --- Sidebar (Creative Glassy Dark) --- */
        .app-sidebar {
            background: #1f2937 !important; /* Elegant Dark */
            box-shadow: 4px 0 25px rgba(0,0,0,0.05);
            border-right: none;
        }

        .sidebar-brand {
            background: rgba(255,255,255,0.03) !important;
            border-bottom: 1px solid rgba(255,255,255,0.05) !important;
            padding: 1.5rem !important;
        }

        .brand-text {
            font-weight: 800 !important;
            letter-spacing: 0.5px;
            color: #fff !important;
        }

        .sidebar-menu .nav-link {
            border-radius: 10px !important;
            margin: 0.25rem 0.75rem;
            padding: 0.75rem 1rem;
            color: #9ca3af !important;
            font-weight: 600;
            transition: var(--transition);
        }

        .sidebar-menu .nav-link:hover {
            background: rgba(255,255,255,0.1) !important;
            color: #fff !important;
            transform: translateX(5px);
        }

        .sidebar-menu .nav-link.active {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark)) !important;
            color: #fff !important;
            box-shadow: 0 4px 15px rgba(67, 97, 238, 0.4);
        }

        .sidebar-menu .nav-icon {
            font-size: 1.1rem;
            margin-left: 0.5rem; /* For RTL */
            opacity: 0.9;
        }

        /* --- Dashboard Widgets (Small Box) --- */
        .small-box {
            border-radius: 20px;
            box-shadow: var(--card-shadow);
            overflow: hidden;
            position: relative;
            background: #fff;
            padding: 20px;
            transition: var(--transition);
            border: 1px solid rgba(0,0,0,0.03);
            margin-bottom: 20px;
        }

        .small-box:hover {
            transform: translateY(-5px);
            box-shadow: var(--hover-shadow);
        }

        .small-box .inner h3 {
            font-weight: 800;
            font-size: 2.5rem;
            color: var(--dark);
            margin-bottom: 5px;
        }

        .small-box .inner p {
            color: #6b7280;
            font-size: 1rem;
            font-weight: 600;
        }

        .small-box .icon {
            position: absolute;
            top: 50%;
            left: 20px; /* RTL switch */
            transform: translateY(-50%);
            font-size: 4rem;
            color: rgba(67, 97, 238, 0.1);
            transition: var(--transition);
        }

        .small-box:hover .icon {
             color: rgba(67, 97, 238, 0.2);
             transform: translateY(-50%) scale(1.1);
        }

        /* Badge Styling */
        .badge {
            padding: 0.5em 0.8em;
            border-radius: 6px;
            font-weight: 600;
            letter-spacing: 0.3px;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        ::-webkit-scrollbar-track {
            background: transparent;
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>

    @stack('styles')
</head>
<body class="layout-fixed sidebar-expand-lg sidebar-open bg-body-tertiary">
    <div class="app-wrapper">
        <!-- Header -->
        <nav class="app-header navbar navbar-expand bg-body">
            <div class="container-fluid">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button">
                            <i class="bi bi-list"></i>
                        </a>
                    </li>
                </ul>
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                         <a class="nav-link" href="#" data-lte-toggle="fullscreen">
                            <i data-lte-icon="maximize" class="bi bi-arrows-fullscreen"></i>
                            <i data-lte-icon="minimize" class="bi bi-fullscreen-exit" style="display: none"></i>
                        </a>
                    </li>
                    
                    <!-- Language Dropdown -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="languageDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-globe"></i>
                            <span class="d-none d-md-inline">{{ __('messages.language') }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="languageDropdown">
                            <li>
                                <a class="dropdown-item {{ app()->getLocale() == 'ar' ? 'active' : '' }}" 
                                   href="{{ route('admin.language.switch', 'ar') }}">
                                    🇸🇦 {{ __('messages.arabic') }}
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item {{ app()->getLocale() == 'en' ? 'active' : '' }}" 
                                   href="{{ route('admin.language.switch', 'en') }}">
                                    🇬🇧 {{ __('messages.english') }}
                                </a>
                            </li>
                        </ul>
                    </li>
                    
                    <li class="nav-item dropdown user-menu">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                           <i class="bi bi-person-circle"></i>
                           <span class="d-none d-md-inline">{{ __('messages.admin') }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                             <li class="user-footer">
                                <a href="#" class="btn btn-default btn-flat float-end">{{ __('messages.logout') }}</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </nav>

        <!-- Sidebar -->
        <aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
            <div class="sidebar-brand">
                <a href="{{ url('/admin') }}" class="brand-link">
                    <span class="brand-text fw-light">{{ __('messages.admin_panel') }}</span>
                </a>
            </div>
            <div class="sidebar-wrapper">
                <nav class="mt-2">
                    <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu" data-accordion="true">
                        
                        <!-- الرئيسية -->
                        <li class="nav-item">
                            <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-speedometer2"></i>
                                <p>{{ __('messages.dashboard') }}</p>
                            </a>
                        </li>

                        <!-- الإدارة -->
                        <li class="nav-item {{ request()->routeIs(['admin.users.*', 'admin.groups.*', 'admin.clients.*', 'admin.accounts.*', 'admin.currencies.*']) ? 'menu-open' : '' }}">
                            <a href="#" class="nav-link {{ request()->routeIs(['admin.users.*', 'admin.groups.*', 'admin.clients.*', 'admin.accounts.*', 'admin.currencies.*']) ? 'active' : '' }}">
                                <i class="nav-icon bi bi-lightning-charge"></i>
                                <p>
                                    {{ __('messages.management') }}
                                    <i class="nav-arrow bi bi-chevron-right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item sub-item">
                                    <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                                        <i class="nav-icon bi bi-people-fill"></i>
                                        <p>{{ __('messages.users') }}</p>
                                    </a>
                                </li>
                                <li class="nav-item sub-item">
                                    <a href="{{ route('admin.groups.index') }}" class="nav-link {{ request()->routeIs('admin.groups.*') ? 'active' : '' }}">
                                        <i class="nav-icon bi bi-collection-fill"></i>
                                        <p>{{ __('messages.groups') }}</p>
                                    </a>
                                </li>
                                <li class="nav-item sub-item">
                                    <a href="{{ route('admin.clients.index') }}" class="nav-link {{ request()->routeIs('admin.clients.*') ? 'active' : '' }}">
                                        <i class="nav-icon bi bi-person-vcard-fill"></i>
                                        <p>{{ __('messages.clients') }}</p>
                                    </a>
                                </li>
                                <li class="nav-item sub-item">
                                    <a href="{{ route('admin.accounts.index') }}" class="nav-link {{ request()->routeIs('admin.accounts.*') ? 'active' : '' }}">
                                        <i class="nav-icon bi bi-wallet2"></i>
                                        <p>{{ __('messages.accounts') }}</p>
                                    </a>
                                </li>
                                <li class="nav-item sub-item">
                                    <a href="{{ route('admin.currencies.index') }}" class="nav-link {{ request()->routeIs('admin.currencies.*') ? 'active' : '' }}">
                                        <i class="nav-icon bi bi-cash-stack"></i>
                                        <p>{{ __('messages.currencies') }}</p>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <!-- المحتوى -->
                        <li class="nav-item {{ request()->routeIs(['admin.slides.*', 'admin.services.*', 'admin.portfolios.*', 'admin.posts.*', 'admin.categories.*', 'admin.skills.*', 'admin.timelines.*']) ? 'menu-open' : '' }}">
                            <a href="#" class="nav-link {{ request()->routeIs(['admin.slides.*', 'admin.services.*', 'admin.portfolios.*', 'admin.posts.*', 'admin.categories.*', 'admin.skills.*', 'admin.timelines.*']) ? 'active' : '' }}">
                                <i class="nav-icon bi bi-file-earmark-text"></i>
                                <p>
                                    {{ __('messages.content') }}
                                    <i class="nav-arrow bi bi-chevron-right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item sub-item">
                                    <a href="{{ route('admin.slides.index') }}" class="nav-link {{ request()->routeIs('admin.slides.*') ? 'active' : '' }}">
                                        <i class="nav-icon bi bi-image-fill"></i>
                                        <p>{{ __('messages.slides') }}</p>
                                    </a>
                                </li>
                                <li class="nav-item sub-item">
                                    <a href="{{ route('admin.services.index') }}" class="nav-link {{ request()->routeIs('admin.services.*') ? 'active' : '' }}">
                                        <i class="nav-icon bi bi-tools"></i>
                                        <p>{{ __('messages.services') }}</p>
                                    </a>
                                </li>
                                <li class="nav-item sub-item">
                                    <a href="{{ route('admin.portfolios.index') }}" class="nav-link {{ request()->routeIs('admin.portfolios.*') ? 'active' : '' }}">
                                        <i class="nav-icon bi bi-briefcase-fill"></i>
                                        <p>{{ __('messages.portfolios') }}</p>
                                    </a>
                                </li>
                                <li class="nav-item sub-item">
                                    <a href="{{ route('admin.posts.index') }}" class="nav-link {{ request()->routeIs('admin.posts.*') ? 'active' : '' }}">
                                        <i class="nav-icon bi bi-file-text-fill"></i>
                                        <p>{{ __('messages.posts') }}</p>
                                    </a>
                                </li>
                                <li class="nav-item sub-item">
                                    <a href="{{ route('admin.categories.index') }}" class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                                        <i class="nav-icon bi bi-bookmark-star-fill"></i>
                                        <p>{{ __('messages.categories') }}</p>
                                    </a>
                                </li>
                                <li class="nav-item sub-item">
                                    <a href="{{ route('admin.skills.index') }}" class="nav-link {{ request()->routeIs('admin.skills.*') ? 'active' : '' }}">
                                        <i class="nav-icon bi bi-star-fill"></i>
                                        <p>{{ __('messages.skills') }}</p>
                                    </a>
                                </li>
                                <li class="nav-item sub-item">
                                    <a href="{{ route('admin.timelines.index') }}" class="nav-link {{ request()->routeIs('admin.timelines.*') ? 'active' : '' }}">
                                        <i class="nav-icon bi bi-clock-history"></i>
                                        <p>{{ __('messages.timelines') }}</p>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <!-- الإعدادات -->
                        <li class="nav-item {{ request()->routeIs(['admin.settings.*']) ? 'menu-open' : '' }}">
                            <a href="#" class="nav-link {{ request()->routeIs(['admin.settings.*']) ? 'active' : '' }}">
                                <i class="nav-icon bi bi-gear"></i>
                                <p>
                                    {{ __('messages.system') }}
                                    <i class="nav-arrow bi bi-chevron-right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item sub-item">
                                    <a href="{{ route('admin.settings.edit') }}" class="nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                                        <i class="nav-icon bi bi-sliders"></i>
                                        <p>{{ __('messages.settings') }}</p>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="nav-item">
    <a href="{{ route('filemanager.index') }}" class="nav-link">
        <i class="nav-icon fas fa-folder-open"></i>
        <p>مدير الملفات</p>
    </a>
</li>

                        <!-- زيارة الموقع -->
                        <li class="nav-item">
                            <a href="{{ url('/') }}" class="nav-link" target="_blank">
                                <i class="nav-icon bi bi-box-arrow-up-left"></i>
                                <p>{{ __('messages.visit_site') }}</p>
                            </a>
                        </li>

                    </ul>
                </nav>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="app-main">
            <div class="app-content-header">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-6">
                            <h3 class="mb-0">@yield('title')</h3>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="app-content">
                <div class="container-fluid">
                     @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                     @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @yield('content')
                </div>
            </div>
        </main>
        
        <footer class="app-footer">
            <strong>جميع الحقوق محفوظة &copy; {{ date('Y') }} .</strong>
        </footer>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" crossorigin="anonymous"></script>
    <script src="{{ asset('adminlte/js/adminlte.js') }}"></script>
    
    @stack('scripts')
</body>
</html>