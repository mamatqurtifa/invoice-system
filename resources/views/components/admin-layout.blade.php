<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Admin - {{ config('app.name', 'Laravel') }} - @yield('title', 'Dashboard')</title>

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
        
        /* Better responsive tables */
        @media (max-width: 640px) {
            .responsive-table {
                display: block;
                width: 100%;
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }
        }
    </style>
    
    @stack('styles')
</head>
<body class="font-sans antialiased bg-gray-50">
    <div x-data="{ sidebarOpen: false }" class="min-h-screen flex">
        <!-- Sidebar -->
        <div 
            :class="{'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen}"
            class="fixed inset-y-0 left-0 z-40 w-64 bg-white shadow-lg transform transition-transform md:translate-x-0 md:relative md:z-0 overflow-y-auto"
        >
            <!-- Logo -->
            <div class="flex items-center justify-center h-16 bg-gray-800 border-b border-gray-700">
                <span class="text-white text-lg font-semibold">Admin Dashboard</span>
            </div>
            
            <!-- Navigation Links -->
            <nav class="px-2 py-4 space-y-1">
                <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }} group flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors duration-200">
                    <i class="fas fa-home mr-3 text-gray-400 group-hover:text-gray-500"></i>
                    Dashboard
                </a>
                
                <a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users.*') ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }} group flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors duration-200">
                    <i class="fas fa-users mr-3 text-gray-400 group-hover:text-gray-500"></i>
                    Users
                </a>
                
                <a href="{{ route('admin.organizations.index') }}" class="{{ request()->routeIs('admin.organizations.*') ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }} group flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors duration-200">
                    <i class="fas fa-building mr-3 text-gray-400 group-hover:text-gray-500"></i>
                    Organizations
                </a>
                
                <a href="{{ route('admin.couriers.index') }}" class="{{ request()->routeIs('admin.couriers.*') ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }} group flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors duration-200">
                    <i class="fas fa-truck mr-3 text-gray-400 group-hover:text-gray-500"></i>
                    Couriers
                </a>
                
                <a href="{{ route('admin.invoices.index') }}" class="{{ request()->routeIs('admin.invoices.*') ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }} group flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors duration-200">
                    <i class="fas fa-file-invoice mr-3 text-gray-400 group-hover:text-gray-500"></i>
                    Invoices
                </a>
                
                <hr class="my-3 border-gray-200">
                
                <!-- Language Selector -->
                <div class="px-3 py-2">
                    <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Language</span>
                    <div class="mt-1 flex space-x-2">
                        <a href="{{ route('language.switch', 'en') }}" class="{{ app()->getLocale() == 'en' ? 'bg-gray-100 text-gray-900' : 'text-gray-500 hover:text-gray-900' }} px-2 py-1 text-sm font-medium rounded">EN</a>
                        <a href="{{ route('language.switch', 'id') }}" class="{{ app()->getLocale() == 'id' ? 'bg-gray-100 text-gray-900' : 'text-gray-500 hover:text-gray-900' }} px-2 py-1 text-sm font-medium rounded">ID</a>
                    </div>
                </div>
            </nav>
        </div>
        
        <!-- Page Content -->
        <div class="flex-1 flex flex-col">
            <!-- Top Navigation -->
            <div class="bg-white shadow z-10">
                <div class="px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex items-center md:hidden">
                            <button @click="sidebarOpen = !sidebarOpen" class="text-gray-500 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-sky-600 hover:text-gray-600 p-2">
                                <span class="sr-only">Open sidebar</span>
                                <i class="fas fa-bars text-lg"></i>
                            </button>
                        </div>
                        
                        <div class="flex items-center">
                            <div class="flex-shrink-0 flex items-center">
                                <a href="{{ route('admin.dashboard') }}">
                                    <img class="h-8 w-auto" src="https://tailwindui.com/img/logos/workflow-mark-sky-600.svg" alt="Workflow">
                                </a>
                            </div>
                        </div>
                        
                        <!-- User Dropdown -->
                        <div class="flex items-center">
                            <div class="ml-3 relative" x-data="{ open: false }">
                                <div>
                                    <button @click="open = !open" class="max-w-xs bg-white flex items-center text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-sky-600" id="user-menu-button" aria-expanded="false" aria-haspopup="true">
                                        <span class="sr-only">Open user menu</span>
                                        @if(Auth::user()->profile_image)
                                            <img class="h-8 w-8 rounded-full object-cover" src="{{ Storage::url(Auth::user()->profile_image) }}" alt="{{ Auth::user()->name }}">
                                        @else
                                            <div class="h-8 w-8 rounded-full bg-sky-600 flex items-center justify-center text-white">
                                                {{ substr(Auth::user()->name, 0, 1) }}
                                            </div>
                                        @endif
                                        <span class="ml-2 text-gray-700 hidden md:block truncate max-w-[100px]">{{ Auth::user()->name }}</span>
                                        <i class="fas fa-chevron-down text-xs ml-2 text-gray-400"></i>
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
                                    <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors duration-200" role="menuitem">Your Profile</a>
                                    
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors duration-200" role="menuitem">Sign out</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
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
            <main class="flex-1 p-6">
                <div class="mb-4">
                    <h1 class="text-2xl font-semibold text-gray-900">@yield('title', 'Dashboard')</h1>
                </div>
                
                {{ $slot }}
            </main>
            
            <!-- Footer -->
            <footer class="bg-white border-t border-gray-100 py-4 px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-sm text-gray-500">
                            &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">
                            Admin Panel v1.0
                        </p>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    
    @stack('scripts')
</body>
</html>