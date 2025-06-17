<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Organization') - {{ config('app.name', 'Invoice Automation System') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Additional Styles -->
    @stack('styles')
</head>
<body class="font-sans antialiased bg-gray-100">
    <div class="min-h-screen">
        <!-- Navigation -->
        <nav class="bg-white border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <!-- Logo -->
                        <div class="shrink-0 flex items-center">
                            <a href="{{ route('organization.dashboard') }}">
                                <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                            </a>
                        </div>

                        <!-- Navigation Links -->
                        <div class="hidden space-x-8 sm:ml-10 sm:flex">
                            <x-nav-link :href="route('organization.dashboard')" :active="request()->routeIs('organization.dashboard')">
                                <i class="fas fa-tachometer-alt mr-1"></i> {{ __('Dashboard') }}
                            </x-nav-link>

                            <x-nav-link :href="route('organization.projects.index')" :active="request()->routeIs('organization.projects.*')">
                                <i class="fas fa-folder-open mr-1"></i> {{ __('Projects') }}
                            </x-nav-link>

                            <x-nav-link :href="route('organization.orders.index')" :active="request()->routeIs('organization.orders.*')">
                                <i class="fas fa-shopping-cart mr-1"></i> {{ __('Orders') }}
                            </x-nav-link>

                            <x-nav-link :href="route('organization.invoices.index')" :active="request()->routeIs('organization.invoices.*')">
                                <i class="fas fa-file-invoice mr-1"></i> {{ __('Invoices') }}
                            </x-nav-link>

                            <x-nav-link :href="route('organization.customers.index')" :active="request()->routeIs('organization.customers.*')">
                                <i class="fas fa-users mr-1"></i> {{ __('Customers') }}
                            </x-nav-link>
                            
                            <x-nav-link :href="route('organization.reports.index')" :active="request()->routeIs('organization.reports.*')">
                                <i class="fas fa-chart-bar mr-1"></i> {{ __('Reports') }}
                            </x-nav-link>
                        </div>
                    </div>

                    <!-- Right Side -->
                    <div class="hidden sm:flex sm:items-center sm:ml-6">
                        <!-- Settings Dropdown -->
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                    <div>{{ Auth::user()->name }}</div>

                                    <div class="ml-1">
                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                <x-dropdown-link :href="route('organization.profile.index')">
                                    <i class="fas fa-building mr-1"></i> {{ __('Organization Profile') }}
                                </x-dropdown-link>
                                
                                <x-dropdown-link :href="route('organization.payment-methods.index')">
                                    <i class="fas fa-credit-card mr-1"></i> {{ __('Payment Methods') }}
                                </x-dropdown-link>
                                
                                <x-dropdown-link :href="route('organization.invoice-templates.index')">
                                    <i class="fas fa-file-invoice mr-1"></i> {{ __('Invoice Templates') }}
                                </x-dropdown-link>
                                
                                <x-dropdown-link :href="route('organization.taxes.index')">
                                    <i class="fas fa-percentage mr-1"></i> {{ __('Tax Rates') }}
                                </x-dropdown-link>
                                
                                <x-dropdown-link :href="route('organization.discounts.index')">
                                    <i class="fas fa-tags mr-1"></i> {{ __('Discounts') }}
                                </x-dropdown-link>

                                <div class="border-t border-gray-200"></div>
                                
                                <x-dropdown-link :href="route('profile.edit')">
                                    <i class="fas fa-user mr-1"></i> {{ __('My Profile') }}
                                </x-dropdown-link>

                                <!-- Authentication -->
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf

                                    <x-dropdown-link :href="route('logout')"
                                            onclick="event.preventDefault();
                                                        this.closest('form').submit();">
                                        <i class="fas fa-sign-out-alt mr-1"></i> {{ __('Log Out') }}
                                    </x-dropdown-link>
                                </form>
                            </x-slot>
                        </x-dropdown>
                    </div>

                    <!-- Hamburger -->
                    <div class="-mr-2 flex items-center sm:hidden">
                        <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Responsive Navigation Menu -->
            <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden" x-data="{ open: false }">
                <div class="pt-2 pb-3 space-y-1">
                    <x-responsive-nav-link :href="route('organization.dashboard')" :active="request()->routeIs('organization.dashboard')">
                        <i class="fas fa-tachometer-alt mr-1"></i> {{ __('Dashboard') }}
                    </x-responsive-nav-link>
                    
                    <x-responsive-nav-link :href="route('organization.projects.index')" :active="request()->routeIs('organization.projects.*')">
                        <i class="fas fa-folder-open mr-1"></i> {{ __('Projects') }}
                    </x-responsive-nav-link>

                    <x-responsive-nav-link :href="route('organization.orders.index')" :active="request()->routeIs('organization.orders.*')">
                        <i class="fas fa-shopping-cart mr-1"></i> {{ __('Orders') }}
                    </x-responsive-nav-link>

                    <x-responsive-nav-link :href="route('organization.invoices.index')" :active="request()->routeIs('organization.invoices.*')">
                        <i class="fas fa-file-invoice mr-1"></i> {{ __('Invoices') }}
                    </x-responsive-nav-link>

                    <x-responsive-nav-link :href="route('organization.customers.index')" :active="request()->routeIs('organization.customers.*')">
                        <i class="fas fa-users mr-1"></i> {{ __('Customers') }}
                    </x-responsive-nav-link>
                    
                    <x-responsive-nav-link :href="route('organization.reports.index')" :active="request()->routeIs('organization.reports.*')">
                        <i class="fas fa-chart-bar mr-1"></i> {{ __('Reports') }}
                    </x-responsive-nav-link>
                </div>

                <!-- Responsive Settings Options -->
                <div class="pt-4 pb-1 border-t border-gray-200">
                    <div class="px-4">
                        <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                        <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                    </div>

                    <div class="mt-3 space-y-1">
                        <x-responsive-nav-link :href="route('organization.profile.index')">
                            <i class="fas fa-building mr-1"></i> {{ __('Organization Profile') }}
                        </x-responsive-nav-link>
                        
                        <x-responsive-nav-link :href="route('organization.payment-methods.index')">
                            <i class="fas fa-credit-card mr-1"></i> {{ __('Payment Methods') }}
                        </x-responsive-nav-link>
                        
                        <x-responsive-nav-link :href="route('organization.invoice-templates.index')">
                            <i class="fas fa-file-invoice mr-1"></i> {{ __('Invoice Templates') }}
                        </x-responsive-nav-link>
                        
                        <x-responsive-nav-link :href="route('organization.taxes.index')">
                            <i class="fas fa-percentage mr-1"></i> {{ __('Tax Rates') }}
                        </x-responsive-nav-link>
                        
                        <x-responsive-nav-link :href="route('organization.discounts.index')">
                            <i class="fas fa-tags mr-1"></i> {{ __('Discounts') }}
                        </x-responsive-nav-link>

                        <x-responsive-nav-link :href="route('profile.edit')">
                            <i class="fas fa-user mr-1"></i> {{ __('My Profile') }}
                        </x-responsive-nav-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-responsive-nav-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                <i class="fas fa-sign-out-alt mr-1"></i> {{ __('Log Out') }}
                            </x-responsive-nav-link>
                        </form>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Page Heading -->
        @if (isset($header))
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endif

        <!-- Page Content -->
        <main>
            <div class="py-6">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    {{ $slot }}
                </div>
            </div>
        </main>
        
        <!-- Footer -->
        <footer class="bg-white border-t border-gray-200 py-4">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center">
                    <div class="text-sm text-gray-500">
                        &copy; {{ date('Y') }} {{ config('app.name', 'Invoice Automation System') }}
                    </div>
                    <div class="text-sm text-gray-500">
                        Version 1.0
                    </div>
                </div>
            </div>
        </footer>
    </div>

    <!-- Scripts -->
    @stack('scripts')
</body>
</html>