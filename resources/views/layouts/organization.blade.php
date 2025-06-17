<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ auth()->user()->organization->name ?? config('app.name') }} - @yield('title', 'Dashboard')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }
        
        html {
            scroll-behavior: smooth;
        }
        
        body {
            font-family: 'Inter', sans-serif;
        }
        
        /* Glass effect backgrounds */
        .bg-glass {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        ::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #a1a1a1;
        }
        
        /* Animations */
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }
        
        .animate-pulse {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
        
        /* Menu hover effects */
        .menu-item {
            position: relative;
        }
        
        .menu-item::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 2px;
            background-color: #0284c7;
            transition: width 0.3s;
        }
        
        .menu-item:hover::after {
            width: 100%;
        }
        
        .menu-item.active::after {
            width: 100%;
        }

        /* Responsive helpers */
        .responsive-container {
            width: 100%;
            padding-left: 1rem;
            padding-right: 1rem;
        }

        @media (min-width: 640px) {
            .responsive-container {
                padding-left: 1.5rem;
                padding-right: 1.5rem;
            }
        }
        
        @media (min-width: 768px) {
            .responsive-container {
                padding-left: 2rem;
                padding-right: 2rem;
            }
        }

        /* Better table responsiveness */
        .responsive-table {
            max-width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
        
        /* For very small screens */
        @media (max-width: 350px) {
            .xs-stack {
                flex-direction: column !important;
                align-items: stretch !important;
            }
            
            .xs-stack > * {
                width: 100% !important;
                margin-left: 0 !important;
                margin-right: 0 !important;
                margin-bottom: 0.5rem !important;
            }
            
            .xs-stack > *:last-child {
                margin-bottom: 0 !important;
            }
        }
    </style>
    
    @stack('styles')
</head>
<body class="font-sans antialiased bg-gray-50">
    <div x-data="{ sidebarOpen: false, dropdownOpen: false }" class="min-h-screen flex flex-col">
        <!-- Top Navigation -->
        <nav class="bg-white shadow-sm sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-2 sm:px-4 lg:px-8">
                <div class="flex justify-between h-16">
                    <!-- Logo and Mobile Menu -->
                    <div class="flex items-center">
                        <div class="flex-shrink-0 flex items-center">
                            @if(auth()->user()->organization && auth()->user()->organization->logo)
                                <img class="h-8 w-auto" src="{{ Storage::url(auth()->user()->organization->logo) }}" alt="{{ auth()->user()->organization->name }}">
                            @else
                                <img class="h-8 w-auto" src="https://tailwindui.com/img/logos/workflow-mark-sky-600.svg" alt="Workflow">
                            @endif
                        </div>
                        <div class="hidden md:ml-6 md:flex space-x-2">
                            <a href="{{ route('organization.dashboard') }}" class="menu-item {{ request()->routeIs('organization.dashboard') ? 'active border-sky-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }} inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors duration-200">
                                Dashboard
                            </a>
                            <a href="{{ route('organization.projects.index') }}" class="menu-item {{ request()->routeIs('organization.projects.*') ? 'active border-sky-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }} inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors duration-200">
                                Projects
                            </a>
                            <a href="{{ route('organization.customers.index') }}" class="menu-item {{ request()->routeIs('organization.customers.*') ? 'active border-sky-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }} inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors duration-200">
                                Customers
                            </a>
                            <a href="{{ route('organization.invoices.index') }}" class="menu-item {{ request()->routeIs('organization.invoices.*') ? 'active border-sky-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }} inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors duration-200">
                                Invoices
                            </a>
                        </div>
                    </div>
                    
                    <!-- Mobile menu button -->
                    <div class="flex items-center md:hidden">
                        <button @click="sidebarOpen = !sidebarOpen" class="text-gray-500 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-sky-600 hover:text-gray-600 p-2">
                            <span class="sr-only">Open sidebar</span>
                            <i class="fas fa-bars text-lg"></i>
                        </button>
                    </div>

                    <!-- User Dropdown and Actions -->
                    <div class="hidden md:flex items-center">
                        <!-- Quick Actions -->
                        <div class="flex items-center mr-4">
                            <a href="{{ route('organization.orders.create') }}" class="inline-flex items-center justify-center px-3 py-1.5 bg-sky-600 text-white text-sm font-medium rounded-lg hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:ring-offset-2 transition-colors duration-200">
                                <i class="fas fa-plus mr-1.5"></i> New Order
                            </a>
                        </div>
                        
                        <!-- Language Selector -->
                        <div class="flex items-center mr-4">
                            <a href="{{ route('language.switch', 'en') }}" class="{{ app()->getLocale() == 'en' ? 'bg-gray-100 text-gray-900' : 'text-gray-500 hover:text-gray-900' }} px-2 py-1 text-sm font-medium rounded transition-colors duration-200">EN</a>
                            <a href="{{ route('language.switch', 'id') }}" class="{{ app()->getLocale() == 'id' ? 'bg-gray-100 text-gray-900' : 'text-gray-500 hover:text-gray-900' }} px-2 py-1 text-sm font-medium rounded transition-colors duration-200">ID</a>
                        </div>
                        
                        <!-- User menu dropdown -->
                        <div x-data="{ open: false }" class="ml-3 relative">
                            <div>
                                <button @click="open = !open" type="button" class="max-w-xs bg-white rounded-full flex items-center text-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-500" id="user-menu-button" aria-expanded="false" aria-haspopup="true">
                                    <span class="sr-only">Open user menu</span>
                                    @if(Auth::user()->profile_image)
                                        <img class="h-8 w-8 rounded-full object-cover" src="{{ Storage::url(Auth::user()->profile_image) }}" alt="{{ Auth::user()->name }}">
                                    @else
                                        <div class="h-8 w-8 rounded-full bg-sky-600 flex items-center justify-center text-white">
                                            {{ substr(Auth::user()->name, 0, 1) }}
                                        </div>
                                    @endif
                                    <span class="ml-2 mr-1 text-gray-700 hidden sm:block truncate max-w-[100px]">{{ Auth::user()->name }}</span>
                                    <i class="fas fa-chevron-down text-xs text-gray-400 ml-1"></i>
                                </button>
                            </div>
                            
                            <div 
                                x-show="open" 
                                @click.away="open = false"
                                x-transition:enter="transition ease-out duration-100"
                                x-transition:enter-start="transform opacity-0 scale-95"
                                x-transition:enter-end="transform opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-75"
                                x-transition:leave-start="transform opacity-100 scale-100"
                                x-transition:leave-end="transform opacity-0 scale-95"
                                class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-50"
                                role="menu"
                                aria-orientation="vertical"
                                aria-labelledby="user-menu-button"
                                tabindex="-1"
                                x-cloak
                            >
                                <a href="{{ route('organization.profile.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors duration-200" role="menuitem">
                                    <i class="fas fa-user mr-2 text-gray-500"></i> Profile
                                </a>
                                
                                <a href="{{ route('organization.profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors duration-200" role="menuitem">
                                    <i class="fas fa-cog mr-2 text-gray-500"></i> Settings
                                </a>
                                
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors duration-200" role="menuitem">
                                        <i class="fas fa-sign-out-alt mr-2 text-gray-500"></i> Sign out
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Mobile navigation menu -->
            <div x-show="sidebarOpen" class="md:hidden bg-white border-t border-gray-200" x-cloak>
                <div class="px-2 pt-2 pb-3 space-y-1">
                    <a href="{{ route('organization.dashboard') }}" class="{{ request()->routeIs('organization.dashboard') ? 'bg-sky-100 text-sky-700' : 'text-gray-700 hover:bg-gray-50' }} block px-3 py-2 rounded-md text-base font-medium transition-colors duration-200">
                        <i class="fas fa-home mr-2"></i> Dashboard
                    </a>
                    
                    <a href="{{ route('organization.projects.index') }}" class="{{ request()->routeIs('organization.projects.*') ? 'bg-sky-100 text-sky-700' : 'text-gray-700 hover:bg-gray-50' }} block px-3 py-2 rounded-md text-base font-medium transition-colors duration-200">
                        <i class="fas fa-project-diagram mr-2"></i> Projects
                    </a>
                    
                    <a href="{{ route('organization.products.index') }}" class="{{ request()->routeIs('organization.products.*') ? 'bg-sky-100 text-sky-700' : 'text-gray-700 hover:bg-gray-50' }} block px-3 py-2 rounded-md text-base font-medium transition-colors duration-200">
                        <i class="fas fa-box mr-2"></i> Products
                    </a>
                    
                    <a href="{{ route('organization.customers.index') }}" class="{{ request()->routeIs('organization.customers.*') ? 'bg-sky-100 text-sky-700' : 'text-gray-700 hover:bg-gray-50' }} block px-3 py-2 rounded-md text-base font-medium transition-colors duration-200">
                        <i class="fas fa-users mr-2"></i> Customers
                    </a>
                    
                    <a href="{{ route('organization.orders.index') }}" class="{{ request()->routeIs('organization.orders.*') ? 'bg-sky-100 text-sky-700' : 'text-gray-700 hover:bg-gray-50' }} block px-3 py-2 rounded-md text-base font-medium transition-colors duration-200">
                        <i class="fas fa-shopping-cart mr-2"></i> Orders
                    </a>
                    
                    <a href="{{ route('organization.invoices.index') }}" class="{{ request()->routeIs('organization.invoices.*') ? 'bg-sky-100 text-sky-700' : 'text-gray-700 hover:bg-gray-50' }} block px-3 py-2 rounded-md text-base font-medium transition-colors duration-200">
                        <i class="fas fa-file-invoice mr-2"></i> Invoices
                    </a>
                    
                    <a href="{{ route('organization.invoice-templates.index') }}" class="{{ request()->routeIs('organization.invoice-templates.*') ? 'bg-sky-100 text-sky-700' : 'text-gray-700 hover:bg-gray-50' }} block px-3 py-2 rounded-md text-base font-medium transition-colors duration-200">
                        <i class="fas fa-file-alt mr-2"></i> Templates
                    </a>
                    
                    <a href="{{ route('organization.payment-methods.index') }}" class="{{ request()->routeIs('organization.payment-methods.*') ? 'bg-sky-100 text-sky-700' : 'text-gray-700 hover:bg-gray-50' }} block px-3 py-2 rounded-md text-base font-medium transition-colors duration-200">
                        <i class="fas fa-credit-card mr-2"></i> Payment Methods
                    </a>
                    
                    <a href="{{ route('organization.reports.index') }}" class="{{ request()->routeIs('organization.reports.*') ? 'bg-sky-100 text-sky-700' : 'text-gray-700 hover:bg-gray-50' }} block px-3 py-2 rounded-md text-base font-medium transition-colors duration-200">
                        <i class="fas fa-chart-bar mr-2"></i> Reports
                    </a>
                    
                    <a href="{{ route('organization.profile.index') }}" class="{{ request()->routeIs('organization.profile.*') ? 'bg-sky-100 text-sky-700' : 'text-gray-700 hover:bg-gray-50' }} block px-3 py-2 rounded-md text-base font-medium transition-colors duration-200">
                        <i class="fas fa-user mr-2"></i> Profile
                    </a>
                    
                    <!-- Add 'New Order' button for mobile -->
                    <div class="mt-2 pt-2 border-t border-gray-200">
                        <a href="{{ route('organization.orders.create') }}" class="block text-center px-3 py-2 rounded-md text-sm font-medium bg-sky-600 text-white hover:bg-sky-700 transition-colors duration-200">
                            <i class="fas fa-plus mr-1.5"></i> New Order
                        </a>
                    </div>
                </div>
                
                <div class="pt-4 pb-3 border-t border-gray-200">
                    <div class="flex items-center px-4">
                        <div class="flex-shrink-0">
                            @if(Auth::user()->profile_image)
                                <img class="h-10 w-10 rounded-full object-cover" src="{{ Storage::url(Auth::user()->profile_image) }}" alt="{{ Auth::user()->name }}">
                            @else
                                <div class="h-10 w-10 rounded-full bg-sky-600 flex items-center justify-center text-white">
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                </div>
                            @endif
                        </div>
                        <div class="ml-3">
                            <div class="text-base font-medium text-gray-800 truncate max-w-[200px]">{{ Auth::user()->name }}</div>
                            <div class="text-sm font-medium text-gray-500 truncate max-w-[200px]">{{ Auth::user()->email }}</div>
                        </div>
                    </div>
                    
                    <div class="mt-3 px-2 space-y-1">
                        <!-- Language Selector -->
                        <div class="flex space-x-1 px-3 py-2">
                            <a href="{{ route('language.switch', 'en') }}" class="{{ app()->getLocale() == 'en' ? 'bg-sky-100 text-sky-700' : 'text-gray-700 hover:bg-gray-50' }} flex-1 block text-center py-1.5 rounded-md text-sm font-medium transition-colors duration-200">
                                English
                            </a>
                            <a href="{{ route('language.switch', 'id') }}" class="{{ app()->getLocale() == 'id' ? 'bg-sky-100 text-sky-700' : 'text-gray-700 hover:bg-gray-50' }} flex-1 block text-center py-1.5 rounded-md text-sm font-medium transition-colors duration-200">
                                Indonesia
                            </a>
                        </div>
                        
                        <form method="POST" action="{{ route('logout') }}" class="px-1">
                            @csrf
                            <button type="submit" class="w-full text-left block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-50 transition-colors duration-200">
                                <i class="fas fa-sign-out-alt mr-2"></i> Sign out
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </nav>
        
        <!-- Side Navigation (permanent on md+) -->
        <div class="flex flex-1 overflow-hidden">
            <div class="hidden md:flex md:w-64 md:flex-col md:fixed md:inset-y-0 mt-16 pt-4">
                <div class="flex-1 flex flex-col min-h-0 bg-white border-r border-gray-200">
                    <div class="flex-1 flex flex-col pt-5 pb-4 overflow-y-auto">
                        <nav class="px-2 space-y-1">
                            <a href="{{ route('organization.dashboard') }}" class="{{ request()->routeIs('organization.dashboard') ? 'bg-sky-100 text-sky-700' : 'text-gray-700 hover:bg-gray-50' }} group rounded-md py-2 px-3 flex items-center text-sm font-medium transition-colors duration-200">
                                <i class="fas fa-tachometer-alt mr-3 text-gray-500 group-hover:text-gray-600"></i>
                                Dashboard
                            </a>
                            
                            <!-- Project Management -->
                            <div x-data="{ open: {{ request()->routeIs('organization.projects.*') || request()->routeIs('organization.products.*') ? 'true' : 'false' }} }">
                                <button @click="open = !open" class="text-gray-700 hover:bg-gray-50 group w-full rounded-md py-2 px-3 flex items-center text-sm font-medium transition-colors duration-200">
                                    <i class="fas fa-project-diagram mr-3 text-gray-500 group-hover:text-gray-600"></i>
                                    Project Management
                                    <i class="fas fa-chevron-down ml-auto text-xs" :class="{'transform rotate-180': open}"></i>
                                </button>
                                
                                <div x-show="open" x-cloak>
                                    <a href="{{ route('organization.projects.index') }}" class="{{ request()->routeIs('organization.projects.*') ? 'bg-sky-50 text-sky-700' : 'text-gray-700 hover:bg-gray-50' }} group rounded-md py-2 pl-10 pr-3 flex items-center text-sm font-medium transition-colors duration-200">
                                        <i class="fas fa-folder mr-3 text-gray-500 group-hover:text-gray-600"></i>
                                        Projects
                                    </a>
                                    
                                    <a href="{{ route('organization.products.index') }}" class="{{ request()->routeIs('organization.products.*') ? 'bg-sky-50 text-sky-700' : 'text-gray-700 hover:bg-gray-50' }} group rounded-md py-2 pl-10 pr-3 flex items-center text-sm font-medium transition-colors duration-200">
                                        <i class="fas fa-box mr-3 text-gray-500 group-hover:text-gray-600"></i>
                                        Products
                                    </a>
                                </div>
                            </div>
                            
                            <a href="{{ route('organization.customers.index') }}" class="{{ request()->routeIs('organization.customers.*') ? 'bg-sky-100 text-sky-700' : 'text-gray-700 hover:bg-gray-50' }} group rounded-md py-2 px-3 flex items-center text-sm font-medium transition-colors duration-200">
                                <i class="fas fa-users mr-3 text-gray-500 group-hover:text-gray-600"></i>
                                Customers
                            </a>
                            
                            <a href="{{ route('organization.orders.index') }}" class="{{ request()->routeIs('organization.orders.*') ? 'bg-sky-100 text-sky-700' : 'text-gray-700 hover:bg-gray-50' }} group rounded-md py-2 px-3 flex items-center text-sm font-medium transition-colors duration-200">
                                <i class="fas fa-shopping-cart mr-3 text-gray-500 group-hover:text-gray-600"></i>
                                Orders
                            </a>
                            
                            <a href="{{ route('organization.invoices.index') }}" class="{{ request()->routeIs('organization.invoices.*') ? 'bg-sky-100 text-sky-700' : 'text-gray-700 hover:bg-gray-50' }} group rounded-md py-2 px-3 flex items-center text-sm font-medium transition-colors duration-200">
                                <i class="fas fa-file-invoice mr-3 text-gray-500 group-hover:text-gray-600"></i>
                                Invoices
                            </a>
                            
                            <!-- Settings -->
                            <div x-data="{ open: {{ request()->routeIs('organization.invoice-templates.*') || request()->routeIs('organization.payment-methods.*') || request()->routeIs('organization.discounts.*') || request()->routeIs('organization.taxes.*') ? 'true' : 'false' }} }">
                                <button @click="open = !open" class="text-gray-700 hover:bg-gray-50 group w-full rounded-md py-2 px-3 flex items-center text-sm font-medium transition-colors duration-200">
                                    <i class="fas fa-cog mr-3 text-gray-500 group-hover:text-gray-600"></i>
                                    Settings
                                    <i class="fas fa-chevron-down ml-auto text-xs" :class="{'transform rotate-180': open}"></i>
                                </button>
                                
                                <div x-show="open" x-cloak>
                                    <a href="{{ route('organization.invoice-templates.index') }}" class="{{ request()->routeIs('organization.invoice-templates.*') ? 'bg-sky-50 text-sky-700' : 'text-gray-700 hover:bg-gray-50' }} group rounded-md py-2 pl-10 pr-3 flex items-center text-sm font-medium transition-colors duration-200">
                                        <i class="fas fa-file-alt mr-3 text-gray-500 group-hover:text-gray-600"></i>
                                        Invoice Templates
                                    </a>
                                    
                                    <a href="{{ route('organization.payment-methods.index') }}" class="{{ request()->routeIs('organization.payment-methods.*') ? 'bg-sky-50 text-sky-700' : 'text-gray-700 hover:bg-gray-50' }} group rounded-md py-2 pl-10 pr-3 flex items-center text-sm font-medium transition-colors duration-200">
                                        <i class="fas fa-credit-card mr-3 text-gray-500 group-hover:text-gray-600"></i>
                                        Payment Methods
                                    </a>
                                    
                                    <a href="{{ route('organization.discounts.index') }}" class="{{ request()->routeIs('organization.discounts.*') ? 'bg-sky-50 text-sky-700' : 'text-gray-700 hover:bg-gray-50' }} group rounded-md py-2 pl-10 pr-3 flex items-center text-sm font-medium transition-colors duration-200">
                                        <i class="fas fa-percentage mr-3 text-gray-500 group-hover:text-gray-600"></i>
                                        Discounts
                                    </a>
                                    
                                    <a href="{{ route('organization.taxes.index') }}" class="{{ request()->routeIs('organization.taxes.*') ? 'bg-sky-50 text-sky-700' : 'text-gray-700 hover:bg-gray-50' }} group rounded-md py-2 pl-10 pr-3 flex items-center text-sm font-medium transition-colors duration-200">
                                        <i class="fas fa-receipt mr-3 text-gray-500 group-hover:text-gray-600"></i>
                                        Taxes
                                    </a>
                                </div>
                            </div>
                            
                            <!-- Reports -->
                            <a href="{{ route('organization.reports.index') }}" class="{{ request()->routeIs('organization.reports.*') ? 'bg-sky-100 text-sky-700' : 'text-gray-700 hover:bg-gray-50' }} group rounded-md py-2 px-3 flex items-center text-sm font-medium transition-colors duration-200">
                                <i class="fas fa-chart-bar mr-3 text-gray-500 group-hover:text-gray-600"></i>
                                Reports
                            </a>
                        </nav>
                    </div>
                </div>
            </div>
            
            <!-- Main Content -->
            <div class="md:ml-64 flex-1 overflow-auto">
                <!-- Flash Messages -->
                <div class="px-4 sm:px-6 lg:px-8 mt-4">
                    @if (session('success'))
                        <x-alert type="success" dismissible>{{ session('success') }}</x-alert>
                    @endif

                    @if (session('error'))
                        <x-alert type="error" dismissible>{{ session('error') }}</x-alert>
                    @endif

                    @if (session('info'))
                        <x-alert type="info" dismissible>{{ session('info') }}</x-alert>
                    @endif

                    @if (session('warning'))
                        <x-alert type="warning" dismissible>{{ session('warning') }}</x-alert>
                    @endif
                </div>
                
                <!-- Main Content -->
                <main class="p-4 sm:p-6 lg:p-8">
                    <div class="mb-4">
                        <h1 class="text-2xl font-semibold text-gray-900">@yield('title', 'Dashboard')</h1>
                        @if (!empty($breadcrumbs))
                            <nav class="flex mt-2 overflow-x-auto pb-1" aria-label="Breadcrumb">
                                <ol class="flex items-center space-x-2 text-sm text-gray-500 flex-nowrap">
                                    <li>
                                        <a href="{{ route('organization.dashboard') }}" class="hover:text-gray-700">Dashboard</a>
                                    </li>
                                    @foreach ($breadcrumbs as $text => $url)
                                        <li>
                                            <i class="fas fa-chevron-right text-xs"></i>
                                        </li>
                                        <li class="whitespace-nowrap">
                                            @if ($loop->last)
                                                <span class="text-gray-900">{{ $text }}</span>
                                            @else
                                                <a href="{{ $url }}" class="hover:text-gray-700">{{ $text }}</a>
                                            @endif
                                        </li>
                                    @endforeach
                                </ol>
                            </nav>
                        @endif
                    </div>
                    
                    {{ $slot }}
                </main>
                
                <!-- Footer -->
                <footer class="bg-white border-t border-gray-100 py-4 px-4 sm:px-6 lg:px-8 mt-auto">
                    <div class="flex flex-col md:flex-row justify-between items-center">
                        <div class="text-center md:text-left mb-2 md:mb-0">
                            <p class="text-sm text-gray-500">
                                &copy; {{ date('Y') }} {{ auth()->user()->organization->name ?? config('app.name') }}. All rights reserved.
                            </p>
                        </div>
                        <div class="text-center md:text-right">
                            <p class="text-sm text-gray-500">
                                Invoice System v1.0
                            </p>
                        </div>
                    </div>
                </footer>
            </div>
        </div>
    </div>
    
    @stack('scripts')
</body>
</html>