@props([
    'type' => 'info',
    'dismissible' => false,
    'title' => null,
    'icon' => null,
])

@php
    $alertClasses = [
        'info' => 'bg-blue-50 border-blue-500 text-blue-700',
        'success' => 'bg-green-50 border-green-500 text-green-700',
        'warning' => 'bg-yellow-50 border-yellow-500 text-yellow-700',
        'error' => 'bg-red-50 border-red-500 text-red-700',
    ];
    
    $defaultIcons = [
        'info' => 'fas fa-info-circle',
        'success' => 'fas fa-check-circle',
        'warning' => 'fas fa-exclamation-triangle',
        'error' => 'fas fa-exclamation-circle',
    ];
    
    $bgColor = $alertClasses[$type] ?? $alertClasses['info'];
    $iconToUse = $icon ?? $defaultIcons[$type] ?? $defaultIcons['info'];
@endphp

<div 
    x-data="{ show: true }"
    x-show="show"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    {{ $attributes->merge(['class' => "border-l-4 p-4 mb-4 {$bgColor}"]) }}
>
    <div class="flex items-start">
        <div class="flex-shrink-0">
            <i class="{{ $iconToUse }}"></i>
        </div>
        
        <div class="ml-3 w-0 flex-1">
            @if($title)
                <h3 class="text-sm font-medium">{{ $title }}</h3>
            @endif
            
            <div class="text-sm">
                {{ $slot }}
            </div>
        </div>
        
        @if($dismissible)
            <div class="ml-auto pl-3">
                <div class="-mx-1.5 -my-1.5">
                    <button 
                        @click="show = false" 
                        class="inline-flex rounded-md p-1.5 focus:outline-none focus:ring-2 focus:ring-offset-2"
                        :class="{
                            'bg-blue-50 text-blue-600 hover:bg-blue-100 focus:ring-blue-600': '{{ $type }}' === 'info',
                            'bg-green-50 text-green-600 hover:bg-green-100 focus:ring-green-600': '{{ $type }}' === 'success',
                            'bg-yellow-50 text-yellow-600 hover:bg-yellow-100 focus:ring-yellow-600': '{{ $type }}' === 'warning',
                            'bg-red-50 text-red-600 hover:bg-red-100 focus:ring-red-600': '{{ $type }}' === 'error',
                        }"
                    >
                        <span class="sr-only">Dismiss</span>
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        @endif
    </div>
</div>