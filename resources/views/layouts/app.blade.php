<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'FoodRescue')</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        :root {
            --primary-color: #28a745;
            --secondary-color: #17a2b8;
            --accent-color: #ffc107;
            --dark-color: #343a40;
        }
        
        .navbar-brand {
            font-weight: 700;
            color: var(--primary-color) !important;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-primary:hover {
            background-color: #218838;
            border-color: #1e7e34;
        }
        
        .card {
            border: none;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            transition: transform 0.2s;
        }
        
        .card:hover {
            transform: translateY(-2px);
        }
        
        .hero-section {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 80px 0;
        }
        
        .stats-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            margin: 10px;
        }
        
        .map-container {
            height: 400px;
            border-radius: 10px;
            overflow: hidden;
        }
        
        .food-card {
            border-left: 4px solid var(--primary-color);
        }
        
        .status-badge {
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
        }
        
        .footer {
            background-color: var(--dark-color);
            color: white;
            padding: 40px 0;
            margin-top: 50px;
        }
        
        .sidebar {
            min-height: calc(100vh - 56px);
            background-color: #f8f9fa;
        }
        
        .nav-pills .nav-link.active {
            background-color: var(--primary-color);
        }
        
        /* Custom Navbar Styles */
        .navbar {
            transition: all 0.3s ease;
            min-height: 70px;
            z-index: 1030;
        }

        .navbar-brand {
            font-weight: 700;
            color: var(--primary-color) !important;
            font-size: 1.5rem;
            transition: all 0.3s ease;
        }

        .navbar-brand:hover {
            transform: scale(1.05);
        }

        .navbar-collapse {
            flex-grow: 1;
        }

        .navbar-nav {
            align-items: center;
        }

        .navbar-nav .nav-link {
            position: relative;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
            border-radius: 8px;
            margin: 0 2px;
            color: #212529 !important;
            font-weight: 500;
        }

        .navbar-nav .nav-link:hover {
            color: var(--primary-color) !important;
            background-color: rgba(40, 167, 69, 0.1);
            transform: translateY(-1px);
        }

        .navbar-nav .nav-link.active {
            color: var(--primary-color) !important;
            font-weight: 600;
            background-color: rgba(40, 167, 69, 0.1);
        }

        .navbar-nav .nav-link.active::after {
            content: '';
            position: absolute;
            bottom: 8px;
            left: 50%;
            transform: translateX(-50%);
            width: 30px;
            height: 3px;
            background: var(--primary-color);
            border-radius: 2px;
        }

        .navbar-toggler {
            border: 1px solid #ddd;
            padding: 0.25rem 0.5rem;
        }

        .navbar-toggler:focus {
            box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
        }

        /* Logout button styles */
        .nav-link.btn.btn-link {
            color: #dc3545 !important;
            text-decoration: none;
            background: transparent;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .nav-link.btn.btn-link:hover {
            background-color: rgba(220, 53, 69, 0.1) !important;
            color: #dc3545 !important;
            transform: translateY(-1px);
        }

        /* Ensure navbar is always visible */
        .navbar {
            display: block !important;
            visibility: visible !important;
        }
        
        .navbar-nav {
            display: flex !important;
            visibility: visible !important;
        }

        .navbar-collapse {
            display: flex !important;
            visibility: visible !important;
        }

        .navbar-collapse.show .navbar-nav {
            display: flex !important;
        }

        /* Force show on large screens */
        @media (min-width: 992px) {
            .navbar-expand-lg .navbar-collapse {
                display: flex !important;
                flex-basis: auto;
            }
            
            .navbar-expand-lg .navbar-nav {
                flex-direction: row;
            }
            
            .navbar-expand-lg .navbar-toggler {
                display: none;
            }
        }

        @media (max-width: 991.98px) {
            .navbar-collapse {
                background: white;
                border-radius: 8px;
                margin-top: 10px;
                padding: 15px;
                box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            }
            
            .navbar-nav .nav-item {
                margin: 5px 0;
            }
            
            .navbar-toggler {
                display: block !important;
            }
        }

        .dropdown-menu {
            border: none;
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
            border-radius: 12px;
            padding: 0.5rem 0;
            margin-top: 0.5rem;
        }

        .dropdown-item {
            padding: 0.5rem 1.5rem;
            transition: all 0.3s ease;
        }

        .dropdown-item:hover {
            background-color: #f8f9fa;
            color: var(--primary-color);
            transform: translateX(5px);
        }

        .dropdown-header {
            color: var(--primary-color);
            font-weight: 600;
            padding: 0.5rem 1.5rem;
        }

        .avatar {
            transition: transform 0.3s ease;
        }

        .avatar:hover {
            transform: scale(1.1);
        }

        .navbar-brand {
            font-size: 1.5rem;
            transition: all 0.3s ease;
        }

        .navbar-brand:hover {
            transform: scale(1.05);
        }

        .badge {
            font-size: 0.7rem;
        }

        .position-relative .badge {
            font-size: 0.6rem;
            padding: 0.2rem 0.4rem;
        }

        @media (max-width: 768px) {
            .navbar-nav .nav-link {
                padding: 0.5rem 1rem;
                text-align: center;
                margin: 2px 0;
            }
            
            .navbar-nav .nav-link.active::after {
                display: none;
            }
            
            .d-none.d-md-inline {
                display: none !important;
            }
        }

        /* Smooth scrolling for anchor links */
        html {
            scroll-behavior: smooth;
        }

        /* Custom button styles */
        .btn-success {
            background: linear-gradient(45deg, var(--primary-color), #20c997);
            border: none;
            transition: all 0.3s ease;
        }

        .btn-success:hover {
            background: linear-gradient(45deg, #218838, #1ea080);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(40, 167, 69, 0.3);
        }

        /* Dark Theme Styles */
        [data-theme="dark"] {
            --primary-color: #34d058;
            --secondary-color: #1ea080;
            --accent-color: #ffd93d;
            --dark-color: #0d1117;
            --bg-color: #161b22;
            --text-color: #e6edf3;
            --card-bg: #21262d;
            --border-color: #30363d;
        }

        [data-theme="dark"] body {
            background-color: var(--bg-color);
            color: var(--text-color);
        }

        [data-theme="dark"] .navbar {
            background-color: var(--card-bg) !important;
            border-bottom: 1px solid var(--border-color);
        }

        [data-theme="dark"] .navbar-brand {
            color: var(--primary-color) !important;
        }

        [data-theme="dark"] .navbar-nav .nav-link {
            color: var(--text-color) !important;
        }

        [data-theme="dark"] .navbar-nav .nav-link:hover {
            color: var(--primary-color) !important;
            background-color: rgba(52, 208, 88, 0.1);
        }

        [data-theme="dark"] .dropdown-menu {
            background-color: var(--card-bg);
            border: 1px solid var(--border-color);
        }

        [data-theme="dark"] .dropdown-item {
            color: var(--text-color);
        }

        [data-theme="dark"] .dropdown-item:hover {
            background-color: rgba(52, 208, 88, 0.1);
            color: var(--primary-color);
        }

        [data-theme="dark"] .card {
            background-color: var(--card-bg);
            border: 1px solid var(--border-color);
        }

        [data-theme="dark"] .hero-section {
            background: linear-gradient(135deg, #1f6feb, #238636);
        }

        [data-theme="dark"] .stats-card {
            background-color: var(--card-bg);
            border: 1px solid var(--border-color);
            color: var(--text-color);
        }

        [data-theme="dark"] .footer {
            background-color: var(--dark-color);
            border-top: 1px solid var(--border-color);
        }

        [data-theme="dark"] .alert {
            background-color: var(--card-bg);
            border: 1px solid var(--border-color);
            color: var(--text-color);
        }

        [data-theme="dark"] .alert-success {
            background-color: rgba(52, 208, 88, 0.1);
            border-color: var(--primary-color);
            color: var(--primary-color);
        }

        [data-theme="dark"] .alert-danger {
            background-color: rgba(248, 81, 73, 0.1);
            border-color: #f85149;
            color: #f85149;
        }

        [data-theme="dark"] .btn-success {
            background: linear-gradient(45deg, var(--primary-color), #20c997);
        }

        [data-theme="dark"] .btn-success:hover {
            background: linear-gradient(45deg, #40d869, #1ea080);
        }

        [data-theme="dark"] .sidebar {
            background-color: var(--card-bg);
            border-right: 1px solid var(--border-color);
        }

        [data-theme="dark"] .text-muted {
            color: #7d8590 !important;
        }

        [data-theme="dark"] .border {
            border-color: var(--border-color) !important;
        }

        [data-theme="dark"] .bg-light {
            background-color: var(--card-bg) !important;
        }

        [data-theme="dark"] .text-dark {
            color: var(--text-color) !important;
        }

        [data-theme="dark"] .navbar-toggler {
            border-color: var(--border-color);
        }

        [data-theme="dark"] .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba%28230, 237, 243, 0.75%29' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='m4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
        }

        /* Theme transition */
        * {
            transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease;
        }

        .theme-toggle, .theme-toggle:before, .theme-icon {
            transition: all 0.3s ease !important;
        }

        /* Theme Toggle Button Styles */
        .theme-toggle {
            position: relative;
            width: 60px;
            height: 30px;
            background: #ddd;
            border-radius: 50px;
            border: none;
            outline: none;
            cursor: pointer;
            transition: all 0.3s ease;
            margin: 0 10px;
        }

        .theme-toggle:before {
            content: '';
            position: absolute;
            top: 3px;
            left: 3px;
            width: 24px;
            height: 24px;
            background: white;
            border-radius: 50%;
            transition: all 0.3s ease;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }

        .theme-toggle.dark {
            background: var(--primary-color);
        }

        .theme-toggle.dark:before {
            transform: translateX(30px);
        }

        .theme-icon {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            font-size: 12px;
            transition: all 0.3s ease;
        }

        .theme-icon.sun {
            left: 8px;
            color: #ffa500;
        }

        .theme-icon.moon {
            right: 8px;
            color: #fff;
        }

        /* Mobile theme toggle */
        @media (max-width: 768px) {
            .theme-toggle {
                width: 50px;
                height: 25px;
                margin: 5px;
            }
            
            .theme-toggle:before {
                width: 19px;
                height: 19px;
                top: 3px;
                left: 3px;
            }
            
            .theme-toggle.dark:before {
                transform: translateX(25px);
            }
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light sticky-top" style="background-color: white !important; border-bottom: 1px solid #dee2e6; min-height: 70px;">
        <div class="container">
            <!-- Brand -->
            <a class="navbar-brand d-flex align-items-center" href="{{ route('home') }}" style="font-weight: 700; color: var(--primary-color) !important; font-size: 1.5rem;">
                <i class="fas fa-leaf text-success me-2"></i>
                <span class="fw-bold text-success">Food</span><span class="text-primary">Rescue</span>
            </a>

            <!-- Mobile Toggle -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" 
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <!-- Left Navigation -->
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}" 
                           style="color: #212529 !important; font-weight: 500; padding: 0.75rem 1rem !important;">
                            <i class="fas fa-home me-1"></i>Home
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('donations.index') ? 'active' : '' }}" href="{{ route('donations.index') }}"
                           style="color: #212529 !important; font-weight: 500; padding: 0.75rem 1rem !important;">
                            <i class="fas fa-map-marked-alt me-1"></i>Food Map
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"
                           style="color: #212529 !important; font-weight: 500; padding: 0.75rem 1rem !important;">
                            <i class="fas fa-info-circle me-1"></i>About
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('how-it-works') }}">
                                <i class="fas fa-cogs me-2"></i>How It Works</a></li>
                            <li><a class="dropdown-item" href="{{ route('impact') }}">
                                <i class="fas fa-chart-line me-2"></i>Our Impact</a></li>
                            <li><a class="dropdown-item" href="{{ route('success-stories') }}">
                                <i class="fas fa-heart me-2"></i>Success Stories</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="{{ route('about') }}">
                                <i class="fas fa-users me-2"></i>About Us</a></li>
                        </ul>
                    </li>
                    @auth
                        @if(auth()->user()->role === 'donor')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/direct-donations-create') }}"
                               style="color: #212529 !important; font-weight: 500; padding: 0.75rem 1rem !important;">
                                <i class="fas fa-plus-circle me-1 text-success"></i>
                                <span class="text-success fw-semibold">Donate Food</span>
                            </a>
                        </li>
                        @endif
                        @if(auth()->user()->role === 'recipient')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('donation-requests.index') }}"
                               style="color: #212529 !important; font-weight: 500; padding: 0.75rem 1rem !important;">
                                <i class="fas fa-hand-holding-heart me-1 text-primary"></i>
                                <span class="text-primary fw-semibold">My Requests</span>
                            </a>
                        </li>
                        @endif
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}"
                               style="color: #212529 !important; font-weight: 500; padding: 0.75rem 1rem !important;">
                                <i class="fas fa-tachometer-alt me-1"></i>Dashboard
                            </a>
                        </li>
                    @endauth
                </ul>
                
                <!-- Right Navigation -->
                <ul class="navbar-nav">
                    <!-- Theme Toggle - Always visible -->
                    <li class="nav-item d-flex align-items-center">
                        <button class="theme-toggle me-2" id="themeToggle" title="Toggle Dark/Light Mode">
                            <i class="fas fa-sun theme-icon sun"></i>
                            <i class="fas fa-moon theme-icon moon"></i>
                        </button>
                    </li>

                    <!-- Emergency Food - Always visible -->
                    <li class="nav-item">
                        <a class="nav-link text-warning" href="{{ route('emergency') }}"
                           style="font-weight: 500; padding: 0.75rem 1rem !important;">
                            <i class="fas fa-exclamation-triangle me-1"></i>Emergency Food
                        </a>
                    </li>
                    
                    @guest
                        <!-- Login/Register for guests -->
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}"
                               style="color: #212529 !important; font-weight: 500; padding: 0.75rem 1rem !important;">
                                <i class="fas fa-sign-in-alt me-1"></i>Login
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-success btn-sm ms-2" href="{{ route('register') }}">
                                <i class="fas fa-user-plus me-1"></i>Join Now
                            </a>
                        </li>
                    @else
                        <!-- Quick Logout for authenticated users -->
                        <li class="nav-item">
                            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="nav-link btn btn-link text-danger border-0" 
                                        style="font-weight: 500; padding: 0.75rem 1rem !important;"
                                        title="Logout and return to homepage">
                                    <i class="fas fa-sign-out-alt me-1"></i>Logout
                                </button>
                            </form>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-1"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-1"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h5><i class="fas fa-seedling me-2"></i>FoodRescue</h5>
                    <p>Connecting food donors with recipients to reduce food waste and help communities in need.</p>
                </div>
                <div class="col-md-4">
                    <h6>Quick Links</h6>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('home') }}" class="text-light">Home</a></li>
                        <li><a href="{{ route('donations.index') }}" class="text-light">Food Map</a></li>
                        <li><a href="#" class="text-light">About Us</a></li>
                        <li><a href="#" class="text-light">Contact</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h6>Contact Info</h6>
                    <p><i class="fas fa-envelope me-2"></i>info@foodrescue.com</p>
                    <p><i class="fas fa-phone me-2"></i>+62 123 456 7890</p>
                    <p><i class="fas fa-map-marker-alt me-2"></i>Malang, Indonesia</p>
                </div>
            </div>
            <hr class="my-4">
            <div class="text-center">
                <p>&copy; {{ date('Y') }} FoodRescue. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    
    <!-- Custom Navbar JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log('FoodRescue navbar initializing...');
            
            // Force show navbar on desktop
            if (window.innerWidth >= 992) {
                const navbarCollapse = document.querySelector('.navbar-collapse');
                if (navbarCollapse) {
                    navbarCollapse.classList.add('show');
                    console.log('Navbar collapse forced to show on desktop');
                }
            }
            
            // Theme Toggle Functionality
            const themeToggle = document.getElementById('themeToggle');
            const html = document.documentElement;
            
            if (themeToggle) {
                // Get saved theme from localStorage or default to light
                const savedTheme = localStorage.getItem('theme') || 'light';
                
                // Apply saved theme on page load
                html.setAttribute('data-theme', savedTheme);
                updateThemeToggle(savedTheme);
                
                // Theme toggle event listener
                themeToggle.addEventListener('click', function() {
                    const currentTheme = html.getAttribute('data-theme');
                    const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
                    
                    html.setAttribute('data-theme', newTheme);
                    localStorage.setItem('theme', newTheme);
                    updateThemeToggle(newTheme);
                    
                    console.log('Theme switched to:', newTheme);
                });
                
                function updateThemeToggle(theme) {
                    if (theme === 'dark') {
                        themeToggle.classList.add('dark');
                        themeToggle.title = 'Switch to Light Mode';
                    } else {
                        themeToggle.classList.remove('dark');
                        themeToggle.title = 'Switch to Dark Mode';
                    }
                }
            }

            // Mobile navbar toggle
            const navbarToggler = document.querySelector('.navbar-toggler');
            if (navbarToggler) {
                navbarToggler.addEventListener('click', function() {
                    const target = document.querySelector(this.getAttribute('data-bs-target'));
                    if (target) {
                        target.classList.toggle('show');
                        console.log('Mobile navbar toggled');
                    }
                });
            }

            // Hover effects for nav links
            const navLinks = document.querySelectorAll('.nav-link');
            navLinks.forEach(link => {
                link.addEventListener('mouseenter', function() {
                    if (!this.classList.contains('active')) {
                        this.style.color = 'var(--primary-color) !important';
                        this.style.backgroundColor = 'rgba(40, 167, 69, 0.1)';
                    }
                });
                
                link.addEventListener('mouseleave', function() {
                    if (!this.classList.contains('active')) {
                        this.style.color = '#212529 !important';
                        this.style.backgroundColor = 'transparent';
                    }
                });
            });

            // Keyboard shortcut for theme toggle (Ctrl/Cmd + Shift + D)
            document.addEventListener('keydown', function(e) {
                if ((e.ctrlKey || e.metaKey) && e.shiftKey && e.key === 'D') {
                    e.preventDefault();
                    if (themeToggle) {
                        themeToggle.click();
                    }
                }
            });

            console.log('FoodRescue navbar initialized successfully');
        });
    </script>
    
    @stack('scripts')
</body>
</html>
