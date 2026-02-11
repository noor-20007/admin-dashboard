<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>لوحة التحكم | @yield('title', 'AdminLTE 4')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes" />
    <meta name="color-scheme" content="light dark" />
    
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
        body {
            font-family: 'Cairo', sans-serif;
        }
        
        /* Premium Small Box Styling */
        .small-box {
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            overflow: hidden;
            position: relative;
            color: #fff !important;
        }
        
        .small-box:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.2);
        }

        .small-box .inner {
            padding: 20px;
            z-index: 2;
            position: relative;
        }

        .small-box h3 {
            font-size: 2.2rem;
            font-weight: 700;
            margin: 0 0 10px 0;
            white-space: nowrap;
        }

        .small-box p {
            font-size: 1.1rem;
            opacity: 0.9;
        }

        .small-box .icon {
            color: rgba(0,0,0,0.15);
            z-index: 0;
            position: absolute;
            right: 15px;
            top: 10px;
            transition: all 0.3s linear;
        }

        .small-box .icon > i {
            font-size: 80px;
        }

        .small-box:hover .icon {
            transform: scale(1.1);
        }

        /* Gradient Backgrounds */
        .bg-info {
            background: linear-gradient(45deg, #17a2b8, #36b9cc) !important;
        }
        .bg-success {
            background: linear-gradient(45deg, #28a745, #1cc88a) !important;
        }
        .bg-warning {
            background: linear-gradient(45deg, #ffc107, #f6c23e) !important;
            color: #fff !important;
        }
        .bg-danger {
            background: linear-gradient(45deg, #dc3545, #e74a3b) !important;
        }
        .bg-primary {
            background: linear-gradient(45deg, #007bff, #4e73df) !important;
        }
        .bg-purple {
            background: linear-gradient(45deg, #6f42c1, #5a5c69) !important;
        }
        
        .card {
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            border: none;
        }
        
        .card-header {
            background-color: transparent;
            border-bottom: 1px solid rgba(0,0,0,0.05);
            padding: 1.2rem 1.5rem;
        }
        
        /* Simple Sidebar Styling */
        .app-sidebar {
            background: #2c3e50 !important;
            box-shadow: 1px 0 8px rgba(0,0,0,0.1);
        }
        
        .sidebar-brand {
            background: rgba(0,0,0,0.2);
            border-bottom: 1px solid rgba(255,255,255,0.1);
            padding: 20px 15px;
        }
        
        .brand-link {
            color: #fff !important;
            text-decoration: none;
        }
        
        .brand-text {
            font-size: 1.2rem;
            font-weight: 600;
            color: #fff;
        }
        
        /* Hide scrollbar completely */
        .sidebar-wrapper {
            overflow-y: auto !important;
            height: calc(100vh - 70px);
        }
        
        .sidebar-wrapper::-webkit-scrollbar {
            width: 6px;
        }
        
        .sidebar-wrapper::-webkit-scrollbar-track {
            background: rgba(255,255,255,0.1);
        }
        
        .sidebar-wrapper::-webkit-scrollbar-thumb {
            background: rgba(255,255,255,0.3);
            border-radius: 3px;
        }
        
        .sidebar-wrapper::-webkit-scrollbar-thumb:hover {
            background: rgba(255,255,255,0.5);
        }
        
        /* Simple Menu Headers */
        .sidebar-menu .nav-header {
            background: rgba(52, 152, 219, 0.7);
            color: white;
            padding: 8px 15px;
            margin: 10px 8px 5px 8px;
            border-radius: 6px;
            font-weight: 600;
            font-size: 0.85rem;
            text-align: center;
        }
        
        .sidebar-menu .nav-item {
            margin: 2px 0;
        }
        
        .sidebar-menu .nav-link {
            border-radius: 6px;
            margin: 0 8px;
            transition: all 0.2s ease;
            color: rgba(255,255,255,0.8) !important;
        }
        
        .sidebar-menu .nav-link:hover {
            background: rgba(255,255,255,0.1);
            color: #fff !important;
        }
        
        .sidebar-menu .nav-link.active {
            background: rgba(39, 174, 96, 0.8);
            color: #fff !important;
        }
        
        .sidebar-menu .nav-icon {
            width: 30px;
            text-align: center;
            font-size: 1.1rem;
        }
        
        .sidebar-menu .nav-link p {
            margin: 0;
            font-weight: 500;
            font-size: 0.95rem;
        }
        
        /* Sub-menu items */
        .sidebar-menu .nav-item.sub-item .nav-link {
            background: rgba(255,255,255,0.03);
            margin-right: 20px;
            font-size: 0.9rem;
        }
        
        .sidebar-menu .nav-item.sub-item .nav-link:hover {
            background: rgba(255,255,255,0.08);
        }
        
        .sidebar-menu .nav-item.sub-item .nav-link.active {
            background: rgba(231, 76, 60, 0.7);
        }
        
        .btn {
            border-radius: 8px;
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
                    <ul class="nav sidebar-menu flex-column" role="navigation">
                        
                        <!-- الرئيسية -->
                        <li class="nav-item">
                            <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-speedometer2"></i>
                                <p>{{ __('messages.dashboard') }}</p>
                            </a>
                        </li>

                        <!-- الإدارة -->
                        <li class="nav-header">⚡ {{ __('messages.management') }}</li>
                        
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

                        <!-- المحتوى -->
                        <li class="nav-header">📝 {{ __('messages.content') }}</li>
                        
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

                        <!-- الإعدادات -->
                        <li class="nav-header">⚙️ {{ __('messages.system') }}</li>
                        
                        <li class="nav-item">
                            <a href="{{ route('admin.settings.edit') }}" class="nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-sliders"></i>
                                <p>{{ __('messages.settings') }}</p>
                            </a>
                        </li>
                        
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