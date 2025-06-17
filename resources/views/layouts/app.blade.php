<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - @yield('title', 'Invoice System')</title>

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
        [x-cloak] {
            display: none !important;
        }

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
        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        .animate-fadeIn {
            animation: fadeIn 0.5s ease-in-out;
        }

        .hover-scale {
            transition: transform 0.2s;
        }

        .hover-scale:hover {
            transform: scale(1.03);
        }

        /* Mobile First Approach */
        .responsive-container {
            @apply w-full px-4 sm:px-6 md:px-8;
        }

        /* Better Table Responsiveness */
        .responsive-table {
            @apply w-full overflow-x-auto;
        }

        /* Card Responsiveness */
        .responsive-card {
            @apply w-full sm:w-auto flex flex-col;
        }

        /* Form Group Responsiveness */
        .form-group {
            @apply mb-4;
        }

        /* Responsive Grid */
        .responsive-grid {
            @apply grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4;
        }

        /* Responsive Stack - row on larger screens, column on mobile */
        .responsive-stack {
            @apply flex flex-col sm:flex-row;
        }

        /* Prevent Text Overflow for Mobile */
        .mobile-truncate {
            @apply truncate max-w-xs;
        }

        /* Responsive Spacing */
        .responsive-padding {
            @apply p-4 sm:p-6;
        }
    </style>

    @stack('styles')
</head>

<body class="font-sans antialiased bg-gray-50 min-h-screen">
    <div class="min-h-screen">
        @include('layouts.navigation')

        <!-- Page Heading -->
        @if (isset($header))
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endif

        <!-- Flash Messages -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
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

        <!-- Page Content -->
        <main class="py-6">
            {{ $slot }}
        </main>

        <!-- Footer -->
        <footer class="bg-white border-t border-gray-100 py-6 mt-auto">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col md:flex-row justify-between items-center">
                    <div class="text-center md:text-left mb-4 md:mb-0">
                        <p class="text-sm text-gray-500">
                            &copy; {{ date('Y') }} {{ config('app.name', 'Laravel') }}. All rights reserved.
                        </p>
                    </div>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-500 hover:text-sky-600 transition-colors duration-200">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="text-gray-500 hover:text-sky-600 transition-colors duration-200">
                            <i class="fab fa-facebook"></i>
                        </a>
                        <a href="#" class="text-gray-500 hover:text-sky-600 transition-colors duration-200">
                            <i class="fab fa-instagram"></i>
                        </a>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    @stack('scripts')
</body>

</html>
