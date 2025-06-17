@props([
    'type' => 'info',
    'dismissible' => false,
])

@php
    $types = [
        'info' => 'bg-blue-50 text-blue-800 border-blue-200',
        'success' => 'bg-green-50 text-green-800 border-green-200',
        'warning' => 'bg-yellow-50 text-yellow-800 border-yellow-200',
        'error' => 'bg-red-50 text-red-800 border-red-200',
    ];

    $icons = [
        'info' => '<i class="fas fa-info-circle"></i>',
        'success' => '<i class="fas fa-check-circle"></i>',
        'warning' => '<i class="fas fa-exclamation-triangle"></i>',
        'error' => '<i class="fas fa-times-circle"></i>',
    ];

    $classes = $types[$type] ?? $types['info'];
@endphp

<div x-data="{ open: true }" 
    x-show="open"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 transform scale-95"
    x-transition:enter-end="opacity-100 transform scale-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 transform scale-100"
    x-transition:leave-end="opacity-0 transform scale-95"
    class="rounded-lg border p-4 mb-4 {{ $classes }}"
    role="alert"
>
    <div class="flex items-start">
        <div class="flex-shrink-0">
            {!! $icons[$type] ?? $icons['info'] !!}
        </div>
        <div class="ml-3">
            <div class="text-sm font-medium">
                {{ $slot }}
            </div>
        </div>
        
        @if($dismissible)
            <div class="ml-auto pl-3">
                <button 
                    type="button"
                    @click="open = false"
                    class="inline-flex rounded-md p-1.5 text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-50"
                >
                    <span class="sr-only">Dismiss</span>
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif
    </div>
</div>